<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Plugins\Savas\Components\Controllers\API;
use ProVallo\Plugins\Savas\Models\Savas\Application\Application;
use ProVallo\Plugins\Savas\Models\Savas\Application\Release;

class ReleaseController extends API
{

    public function configure()
    {
        return [
            'model' => Release::class
        ];
    }

    public function getListQuery()
    {
        $userID = self::auth()->getUserID();
        $appID  = self::request()->getParam('applicationID');
        $query  = self::db()->from('s_application a')
            ->leftJoin('s_application_member am ON am.appID = a.id')
            ->innerJoin('s_application_release ar ON ar.appID = a.id')
            ->leftJoin('s_application_release_file arf ON arf.releaseID = ar.id')
            ->where('am.userID', $userID)
            ->where('a.id', $appID)
            ->select(null)->select('ar.*, COUNT(arf.id) AS files')
            ->orderBy('ar.version DESC')
            ->groupBy('ar.id');

        if ($this->isApiCall)
        {
            $query->select('(SELECT label FROM s_channel WHERE id = ar.channelID) AS channel_label');
        }

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity, $action)
    {
        return Application::isMember($entity->appID);
    }

    public function setDefaultValues (\Favez\ORM\Entity\Entity $entity)
    {
        $entity->created = date('Y-m-d H:i:s');
        $entity->changed = date('Y-m-d H:i:s');
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        if ($this->isApiCall)
        {
            // Allow the user to enter the name of the channel for better usability
            $input['channelID'] = (int) self::db()->from('s_channel')
                ->where('label = ?', $input['channel'] ?? '')
                ->fetchColumn(0);
        }

        $entity->set('appID', $input['appID'] ?? 0);
        $entity->set('channelID', $input['channelID'] ?? 0);
        $entity->set('active', (int) $input['active'] ?? false);
        $entity->set('version', $input['version'] ?? '');
        $entity->set('description', $input['description'] ?? '');
    }

}