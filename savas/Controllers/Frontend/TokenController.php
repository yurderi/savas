<?php

namespace CMS\Controllers\Frontend;

use savas\Components\Controllers\API;
use savas\Models\Savas\Token\Token;

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
        $userID = self::auth()->userID();
        $query  = self::db()->from('s_api_token')
            ->where('userID = ?', $userID);

        return $query;
    }

    public function checkPermission (\Favez\ORM\Entity\Entity $entity)
    {
        return (int) $entity->userID === self::auth()->userID();
    }

    public function setDefaultValues(\Favez\ORM\Entity\Entity $entity)
    {
        $entity->set('userID', self::auth()->userID());
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