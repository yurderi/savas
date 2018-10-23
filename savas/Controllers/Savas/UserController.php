<?php

namespace CMS\Controllers\Savas;

use CMS\Components\Controller;
use CMS\Models\User\User;
use Validator\Validator;

class UserController extends Controller
{

    public function loginAction ()
    {
        $email    = self::request()->getParam('email');
        $password = self::request()->getParam('password');
        $register = self::request()->getParam('register');

        if ($register === true)
        {
            return $this->register($email, $password);
        }
        else if (self::auth()->login($email, $password, false))
        {
            return self::json()->success();
        }

        return self::json()->failure([
            'error' => 'The email or password you entered is wrong.'
        ]);
    }
    
    public function logoutAction ()
    {
        self::auth()->clear();
        
        return self::json()->success();
    }

    public function statusAction ()
    {
        return self::json()->success([
            'loggedIn' => self::auth()->loggedIn(),
            'config' => [
                'enableRegister' => true
            ]
        ]);
    }

    protected function register ($email, $password)
    {
        Validator::addGlobalRule('unique_email', function ($fields, $value, $params) {
            return User::repository()->findOneBy(['email' => $value]) === false;
        });

        $validator = new Validator();
        $validator->add('email', $email, 'required|email|unique_email', [
            'required'     => 'Please enter a valid email address',
            'email'        => 'Please enter a valid email address',
            'unique_email' => 'The entered email address is already in use'
        ]);

        $validator->add('password', $password, 'required|min:8', [
            'required' => 'Please enter a password',
            'min'      => 'The password has a minimum length of 8 characters'
        ]);

        $validator->validate();

        if ($validator->passes())
        {
            /** @var User $user */
            $user = User::create();
            $user->email        = $email;
            $user->passwordHash = self::auth()->hash($password);
            $user->firstname    = '';
            $user->lastname     = '';
            $user->changed      = date('Y-m-d H:i:s');
            $user->created      = date('Y-m-d H:i:s');
            $user->groupID      = 1;

            $user->save();

            self::auth()->login($email, $password);

            return self::json()->success();
        }

        return self::json()->failure([
            'error' => $validator->errors()[0]
        ]);
    }

}