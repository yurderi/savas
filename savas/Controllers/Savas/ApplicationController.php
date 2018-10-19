<?php

namespace CMS\Controllers\Savas;

use savas\Components\Controllers\API;
use savas\Models\Savas\Application\Application;
use savas\Models\Savas\Application\Member;

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
        $userID = self::auth()->userID();
        $query  = self::db()->from('s_application a')
            ->leftJoin('s_application_member am ON am.appID = a.id')
            ->where('am.userID', $userID)
            ->select(null)->select('a.*')
            // For current version
            ->join('s_channel c ON c.userID = am.userID AND main = 1')
            ->join('s_application_release ar ON ar.appID = a.id AND ar.channelID = c.id')
            ->select('ar.version AS currentVersion')
            // For release count
            ->join('s_application_release ar2 ON ar.appID = a.id')
            ->select('COUNT(ar.id) AS releaseCount')
            ->groupBy('ar.id')
            // For downloadCount
            ->select('0 AS downloadCount')
            // For feedbackCount
            ->select('0 AS feedbackCount');

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity)
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
            $member->userID = self::auth()->userID();
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