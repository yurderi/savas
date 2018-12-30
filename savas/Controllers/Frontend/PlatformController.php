<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Plugins\Savas\Components\Controllers\API;
use ProVallo\Plugins\Savas\Models\Savas\Platform;

class PlatformController extends API
{

    public function configure()
    {
        return [
            'model' => Platform::class
        ];
    }

    public function getListQuery()
    {
        $userID = self::auth()->getUserID();
        $query  = self::db()->from('s_platform')
            ->where('userID = -1 OR userID = ?', $userID);

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity, $action)
    {
        return (int) $entity->userID === self::auth()->getUserID();
    }

    public function setDefaultValues(\Favez\ORM\Entity\Entity $entity)
    {
        $entity->set('userID', self::auth()->getUserID());
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        $entity->set('label', $input['label']);
    }

}