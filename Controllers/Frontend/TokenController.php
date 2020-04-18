<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Plugins\Savas\Components\Controllers\API;
use ProVallo\Plugins\Savas\Models\Savas\Token\Token;

class TokenController extends API
{

    public function configure()
    {
        return [
            'model' => Token::class
        ];
    }

    public function getListQuery()
    {
        $userID = self::auth()->getUserID();
        $query  = self::db()->from('s_api_token')
            ->where('userID = ?', $userID);

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity, $action)
    {
        return (int) $entity->userID === self::auth()->getUserID();
    }

    public function setDefaultValues(\Favez\ORM\Entity\Entity $entity)
    {
        $entity->set('userID', self::auth()->getUserID());
        $entity->set('created', date('Y-m-d H:i:s'));
        $entity->set('token', md5(uniqid('savas_api_token')));
    }

    public function setValues (\Favez\ORM\Entity\Entity $entity, $input)
    {
        $entity->set('changed', date('Y-m-d H:i:s'));
        $entity->set('enabled', (int) $input['enabled']);
        $entity->set('label', $input['label']);
    }

}