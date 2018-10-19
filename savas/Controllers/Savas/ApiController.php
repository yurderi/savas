<?php

namespace CMS\Controllers\Savas;

use CMS\Components\Controller;
use savas\Models\Savas\Application\Application;
use savas\Models\Savas\Application\File;
use savas\Models\Savas\Channel;
use savas\Models\Savas\Platform;

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
        $userID = 1; // Todo: load the userID dynamically

        if ($app instanceof Application)
        {
            if ($app->visibility === 'private' && $token !== $app->publicKey)
            {
                return self::json()->failure([
                    'code'    => 403,
                    'message' => 'You are not permitted to access this application.'
                ]);
            }

            $channel = Channel::repository()->findOneBy(['label' => $channel, 'userID' => $userID]);

            if (!($channel instanceof Channel))
            {
                return self::json()->failure([
                    'code'    => 404,
                    'message' => 'The provided channel does not exist.'
                ]);
            }

            $platform = Platform::repository()->findOneBy(['label' => $platform, 'userID' => $userID]);

            if (!($platform instanceof Platform))
            {
                return self::json()->failure([
                    'code'    => 404,
                    'message' => 'The provided platform does not exist.'
                ]);
            }

            $releases = self::db()->from('s_application_release')
                ->where('appID', $app->id)
                ->where('channelID', $channel->id)
                ->where('active = 1');

            foreach ($releases as $release)
            {
                if (empty($version) || version_compare($release['version'], $version, '>'))
                {
                    $file = File::repository()->findOneBy([
                        'releaseID'  => $release['id'],
                        'platformID' => $platform->id
                    ]);

                    if (!($file instanceof File))
                    {
                        return self::json()->failure([
                            'code'    => 404,
                            'message' => 'The associated release file does not exist.'
                        ]);
                    }

                    return self::json()->success([
                        'version'  => $release['version'],
                        'released' => $release['created'],
                        'size'     => $file->size,
                        'filename' => self::url('savas/api/download/' . $file->displayName . '?id=' . $file->filename),
                        'isNewer'  => true
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

}