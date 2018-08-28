<?php

namespace CMS\Controllers\Savas;

use savas\Components\Controllers\API;
use savas\Models\Savas\Channel;

class ChannelController extends API
{

    public function configure()
    {
        return [
            'model' => Channel::class
        ];
    }

    public function getListQuery()
    {
        $userID = self::auth()->userID();
        $query  = self::db()->from('s_channel')
            ->where('userID = -1 OR userID = ?', $userID);

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity)
    {
        return $entity->userID === self::auth()->userID();
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        $entity->set('label', $input['label']);
        $entity->set('description', $input['description']);
    }

}