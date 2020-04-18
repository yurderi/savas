<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Plugins\Savas\Components\Controllers\API;
use ProVallo\Plugins\Savas\Models\Savas\Application\Application;
use ProVallo\Plugins\Savas\Models\Savas\Application\Member;

class ApplicationController extends API
{

    public function configure()
    {
        return [
            'model' => Application::class
        ];
    }

    public function getListQuery()
    {
        $userID = self::auth()->getUserID();
        $query  = self::db()->from('s_application a')
            ->leftJoin('s_application_member am ON am.appID = a.id')
            ->where('am.userID', $userID)
            ->select(null)->select('a.*')
            // For current version
            ->select('(SELECT version FROM s_application_release WHERE appID = am.id ORDER BY id DESC LIMIT 1) AS currentVersion')
            // For release count
            ->select('(SELECT COUNT(id) FROM s_application_release WHERE appID = am.id) AS releaseCount')
            // For downloadCount
            ->select('0 AS downloadCount')
            // For feedbackCount
            ->select('0 AS feedbackCount');

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity, $action)
    {
        return Application::isMember($entity->id);
    }

    public function setDefaultValues (\Favez\ORM\Entity\Entity $entity)
    {
        $entity->created    = date('Y-m-d H:i:s');
        $entity->changed    = date('Y-m-d H:i:s');
        $entity->publicKey  = md5(uniqid('publicKey'));
        $entity->privateKey = md5(uniqid('privateKey'));
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        $entity->set('label', $input['label']);
        $entity->set('description', $input['description']);
        $entity->set('visibility', $input['visibility']);
    }

    public function afterSave (\Favez\ORM\Entity\Entity $entity, $isNew)
    {
        if ($isNew)
        {
            $member = Member::create();
            $member->userID = self::auth()->getUserID();
            $member->appID  = $entity->id;
            $member->save();
        }
    }

    public function checkLabelAction ()
    {
        $id    = self::request()->getParam('id');
        $label = self::request()->getParam('label');

        return self::json()->success([
            'success' => true,
            'used'    => !Application::isUniqueLabel($id, $label)
        ]);
    }

}