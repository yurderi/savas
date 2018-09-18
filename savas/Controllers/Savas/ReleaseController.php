<?php

namespace CMS\Controllers\Savas;

use savas\Components\Controllers\API;
use savas\Models\Savas\Application\Application;
use savas\Models\Savas\Application\Release;

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
        $userID = self::auth()->userID();
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

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity)
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
        $entity->set('appID', $input['appID']);
        $entity->set('channelID', $input['channelID']);
        $entity->set('version', $input['version']);
        $entity->set('description', $input['description']);
    }

    public function afterSave (\Favez\ORM\Entity\Entity $entity, $isNew)
    {

    }

}