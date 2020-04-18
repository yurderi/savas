<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Plugins\Backend\Models\User\User;
use Validator\Validator;

/**
 * Class UserController
 *
 * @package ProVallo\Controllers\Frontend
 *
 * @method \ProVallo\Plugins\Backend\Components\Auth auth()
 */
class UserController extends \ProVallo\Components\Controller
{
    
    public function loginAction ()
    {
        $username = self::request()->getParam('username');
        $password = self::request()->getParam('password');
        
        try
        {
            self::auth()->login($username, $password);
            
            return self::json()->success();
        }
        catch (\Exception $ex)
        {
            return self::json()->failure([
                'error' => 'The email or password you entered is wrong.'
            ]);
        }
    }
    
    public function logoutAction ()
    {
        self::auth()->logout();
        
        return self::json()->success();
    }
    
    public function statusAction ()
    {
        return self::json()->success([
            'loggedIn' => self::auth()->isLoggedIn(),
            'config'   => [
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
            $user               = User::create();
            $user->email        = $email;
            $user->passwordHash = self::auth()->hash($password);
            $user->firstname    = '';
            $user->lastname     = '';
            $user->changed      = date('Y-m-d H:i:s');
            $user->created      = date('Y-m-d H:i:s');
            $user->groupID      = 1;
            
            $user->save();
            
            try
            {
                self::auth()->login($email, $password);
                
                return self::json()->success();
            }
            catch (\Exception $ex)
            {
                return self::json()->failure([
                    'error' => 'The email or password you entered is wrong.'
                ]);
            }
        }
        
        return self::json()->failure([
            'error' => $validator->errors()[0]
        ]);
    }
    
}