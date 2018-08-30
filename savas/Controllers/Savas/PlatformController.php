<?php

namespace CMS\Controllers\Savas;

use savas\Components\Controllers\API;
use savas\Models\Savas\Platform;

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
        $userID = self::auth()->userID();
        $query  = self::db()->from('s_platform')
            ->where('userID = -1 OR userID = ?', $userID);

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity)
    {
        return (int) $entity->userID === self::auth()->userID();
    }

    public function setDefaultValues(\Favez\ORM\Entity\Entity $entity)
    {
        $entity->set('userID', self::auth()->userID());
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        $entity->set('label', $input['label']);
        $entity->set('description', $input['description']);
    }

}