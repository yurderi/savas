<?php

namespace CMS\Controllers\Frontend;

use Favez\ORM\Entity\Entity;
use savas\Components\Controllers\API;
use savas\Models\Savas\Token;

class ChannelController extends API
{

    public function configure()
    {
        return [
            'model' => Token::class
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
        return (int) $entity->userID === self::auth()->userID();
    }

    public function setDefaultValues(\Favez\ORM\Entity\Entity $entity)
    {
        $entity->set('userID', self::auth()->userID());
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        $entity->set('label', $input['label']);
        $entity->set('short', $input['short']);
        $entity->set('main', (int) $input['main']);
    }

}