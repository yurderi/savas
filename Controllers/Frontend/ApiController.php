<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Components\Controller;
use ProVallo\Plugins\Savas\Bootstrap;
use ProVallo\Plugins\Savas\Models\Savas\Application\Application;
use ProVallo\Plugins\Savas\Models\Savas\Application\File;
use ProVallo\Plugins\Savas\Models\Savas\Token\Token;

class ApiController extends Controller
{
    
    private const MODE_SINGLE = 1;
    
    private const MODE_ALL    = 2;
    
    public function searchAction ()
    {
        // todo: make apps searchable through shared stream
        
        $config = Bootstrap::getConfig();
        
        if ($config['search_api'] !== true)
        {
            return self::json()->failure([
                'message' => 'The search API is not activated.'
            ]);
        }
        
        $channel  = (string) self::request()->getParam('channel');
        $platform = (string) self::request()->getParam('platform');
        $search   = (string) self::request()->getParam('search');
        
        $sql = '
            SELECT MAX(a.label) AS label, MAX(a.description) AS description, MAX(ar.version) AS latestVersion, MAX(ar.created) AS created
            FROM s_application a
            LEFT JOIN s_application_release ar ON ar.appID = a.id
            LEFT JOIN s_channel c ON c.id = ar.channelID
            LEFT JOIN s_application_release_file arf ON arf.releaseID = ar.id
            LEFT JOIN s_platform p ON p.id = arf.platformID
            WHERE a.visibility = "public"
              AND ar.active = 1
              AND c.label = ?
              AND p.label = ?
            GROUP BY a.id
        ';
        
        $params = [
            $channel,
            $platform
        ];
        
        if (!empty($search))
        {
            $sql = str_replace('GROUP BY a.id', 'AND a.label LIKE ? GROUP BY a.id', $sql);
            $params[] = '%' . $search . '%';
        }
        
        $stmt = self::db()->query($sql);
        $stmt->execute($params);
        
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return self::json()->success([
            'data'  => $results,
            'total' => count($results)
        ]);
    }
    
    /**
     * Path: /api/updates
     * Params:
     *     id       - technical application name
     *     channel  - the technical channel name
     *     platform - the technical platform name
     *     version  - the current installed version
     *     token    - the public token (required if the app is private)
     */
    public function updatesAction ()
    {
        $id       = (string) self::request()->getParam('id');
        $channel  = (string) self::request()->getParam('channel');
        $platform = (string) self::request()->getParam('platform');
        $version  = (string) self::request()->getParam('version');
        $token    = (string) self::request()->getParam('token');
        $mode     = (int) self::request()->getParam('mode', self::MODE_SINGLE);
        $app      = Application::repository()->findOneBy(['label' => $id]);
        
        if ($app instanceof Application)
        {
            if ($app->visibility === 'private' && $token !== $app->publicKey)
            {
                return self::json()->failure([
                    'code'    => 403,
                    'message' => 'You are not permitted to access this application.'
                ]);
            }
            
            switch ($mode)
            {
                case self::MODE_SINGLE:
                    $releases = self::db()->from('s_application_release r')
                        ->join('s_channel c ON c.id = r.channelID')
                        ->where('r.appID', $app->id)
                        ->where('c.label', $channel)
                        ->where('r.active = 1')
                        ->orderBy('r.version DESC')
                        ->fetchAll();
    
                    foreach ($releases as $release)
                    {
                        if (empty($version) || version_compare($release['version'], $version, '>'))
                        {
                            $file = self::db()->from('s_application_release_file f')
                                ->where('f.releaseID = ?', $release['id'])
                                ->join('s_platform p ON p.id = f.platformID')
                                ->where('p.label = ?', $platform)
                                ->fetch();
            
                            if (empty($file))
                            {
                                return self::json()->failure([
                                    'code'    => 404,
                                    'message' => 'The associated release file does not exist.'
                                ]);
                            }
            
                            return self::json()->success([
                                'version'      => $release['version'],
                                'released'     => $release['created'],
                                'size'         => $file['size'],
                                'releaseNotes' => $release['description'],
                                'filename'     => self::url('api/v1/download/' . $file['displayName'] . '?id=' . $file['filename']),
                
                                'isNewer' => true
                            ]);
                        }
                    }
                break;
                case self::MODE_ALL:
                    $releases = self::db()->from('s_application_release r')
                        ->join('s_channel c ON c.id = r.channelID')
                        ->where('r.appID', $app->id)
                        ->where('c.label', $channel)
                        ->where('r.active = 1')
                        ->orderBy('r.version DESC')
                        ->fetchAll();
                    
                    $result = [];
    
                    foreach ($releases as $release)
                    {
                        $file = self::db()->from('s_application_release_file f')
                            ->where('f.releaseID = ?', $release['id'])
                            ->join('s_platform p ON p.id = f.platformID')
                            ->where('p.label = ?', $platform)
                            ->fetch();
    
                        if (empty($file))
                        {
                            return self::json()->failure([
                                'code'    => 404,
                                'message' => 'The associated release file does not exist.'
                            ]);
                        }
                        
                        $result[] = [
                            'version'      => $release['version'],
                            'released'     => $release['created'],
                            'size'         => $file['size'],
                            'releaseNotes' => $release['description'],
                            'filename'     => self::url('api/v1/download/' . $file['displayName'] . '?id=' . $file['filename']),
                        ];
                    }
    
                    return self::json()->success([
                        'data' => $result,
                    ]);
                break;
                default;
                    return self::json()->failure([
                        'message' => 'Invalid mode. Accepting MODE_SINGLE and MODE_ALL'
                    ]);
            }
            
            return self::json()->success([
                'isNewer' => false
            ]);
        }
        else
        {
            return self::json()->failure([
                'code'    => 404,
                'message' => 'The application you requested does not exist.'
            ]);
        }
    }
    
    /**
     * Path: /api/requirements
     * Params:
     *     id       - technical application name
     *     channel  - the technical channel name
     *     platform - the technical platform name
     *     version  - the current installed version
     *     token    - the public token (required if the app is private)
     */
    public function requirementsAction ()
    {
        $id       = (string) self::request()->getParam('id');
        $channel  = (string) self::request()->getParam('channel');
        $platform = (string) self::request()->getParam('platform');
        $version  = (string) self::request()->getParam('version');
        $token    = (string) self::request()->getParam('token');
        
        $app = Application::repository()->findOneBy(['label' => $id]);
        
        if ($app instanceof Application)
        {
            if ($app->visibility === 'private' && $token !== $app->publicKey)
            {
                return self::json()->failure([
                    'code'    => 403,
                    'message' => 'You are not permitted to access this application.'
                ]);
            }
            
            $releases = self::db()->from('s_application_release r')
                ->join('s_channel c ON c.id = r.channelID')
                ->where('r.appID', $app->id)
                ->where('c.label', $channel)
                ->where('r.active = 1')
                ->orderBy('r.version DESC')
                ->fetchAll();
            
            foreach ($releases as $i => &$release)
            {
                if (!version_compare($release['version'], $version, '>'))
                {
                    unset ($releases[$i]);
                }
                
                $file = self::db()->from('s_application_release_file f')
                    ->where('f.releaseID = ?', $release['id'])
                    ->join('s_platform p ON p.id = f.platformID')
                    ->where('p.label = ?', $platform)
                    ->fetch();
                
                if (empty($file))
                {
                    return self::json()->failure([
                        'code'    => 404,
                        'message' => 'The associated release file does not exist.'
                    ]);
                }
                
                $release['filename']     = self::url('api/v1/download/' . $file['displayName'] . '?id=' . $file['filename']);
                $release['releaseNotes'] = $release['description'];
                $release['size']         = $file['size'];
                $release['released']     = $release['created'];
                $release['requirements'] = json_decode($file['systemRequirements'], true);
                
                unset ($release['id']);
                unset ($release['appID']);
                unset ($release['channelID']);
                unset ($release['active']);
                unset ($release['created']);
                unset ($release['changed']);
                unset ($release['description']);
            }
            
            return self::json()->success([
                'releases' => $releases
            ]);
        }
        else
        {
            return self::json()->failure([
                'code'    => 404,
                'message' => 'The application you requested does not exist.'
            ]);
        }
    }
    
    public function downloadAction ()
    {
        $id   = (string) self::request()->getParam('id');
        $file = File::repository()->findOneBy(['filename' => $id]);
        
        if ($file instanceof File)
        {
            $filename = self::path() . 'media/savas/' . $file->filename;
            
            if (is_file($filename))
            {
                header('Content-Type: ' . $file->mimeType);
                readfile($filename);
                die;
            }
        }
        
        return '404 Not Found';
    }
    
    public function authAction ()
    {
        $token = self::request()->getParam('api_token');
        $model = Token::repository()->findOneBy(['token' => $token]);
        
        if ($model instanceof Token)
        {
            $applications = self::db()->from('s_application a')
                ->join('s_application_member m ON m.appID = a.id')
                ->where('m.userID = ?', $model->userID)
                ->select(null)->select('a.id, a.label')
                ->fetchAll();
            
            return self::json()->success([
                'apps' => $applications
            ]);
        }
        
        return self::json()->failure([
            'message' => 'Invalid token'
        ]);
    }
    
}