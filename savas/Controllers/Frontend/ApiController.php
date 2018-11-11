<?php

namespace CMS\Controllers\Frontend;

use CMS\Components\Controller;
use savas\Models\Savas\Application\Application;
use savas\Models\Savas\Application\File;
use savas\Models\Savas\Token\Token;

class ApiController extends Controller
{

    /**
     * Path: /api/updates
     * Params:
     *     id      - technical application name
     *     channel - the technical channel name
     *     version - the current installed version
     *     token   - the public token (required if the app is private)
     */
    public function updatesAction()
    {
        $id       = (string) self::request()->getParam('id');
        $channel  = (string) self::request()->getParam('channel');
        $platform = (string) self::request()->getParam('platform');
        $version  = (string) self::request()->getParam('version');
        $token    = (string) self::request()->getParam('token');

        $app    = Application::repository()->findOneBy(['label' => $id]);

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

                        'isNewer'      => true
                    ]);
                }
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

    public function downloadAction()
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

    public function authAction()
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