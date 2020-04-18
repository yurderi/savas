<?php

namespace ProVallo\Plugins\Savas\Components\Controllers;

use ProVallo\Plugins\Backend\Models\User\User;
use ProVallo\Plugins\Savas\Models\Savas\Token\Token;

abstract class API extends \ProVallo\Plugins\Backend\Components\Controllers\API
{
    
    /**
     * @var boolean
     */
    protected $isApiCall;
    
    protected function isLoggedIn ()
    {
        if ($this->isAuthenticatedByToken())
        {
            return true;
        }
        
        return parent::isLoggedIn();
    }

    public function isAuthenticatedByToken ()
    {
        $token = self::request()->getHeaderLine('X-API-Token');
        $model = Token::repository()->findOneBy(['token' => $token]);

        if ($model instanceof Token)
        {
            /** @var User $user */
            $user = User::repository()->find($model->userID);
            /** @var \ProVallo\Plugins\Backend\Components\Auth $auth */
            $auth = self::auth();
            $auth->setUser($user);

            $this->isApiCall = true;

            return true;
        }

        return false;
    }

}