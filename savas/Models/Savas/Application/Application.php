<?php

namespace savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;
use Validator\Validator;

class Application extends Entity
{

    const SOURCE = 's_application';

    public $id;

    public $label;

    public $description;

    public $privateKey;

    public $publicKey;

    public $created;

    public $changed;

    public function initialize()
    {
        $this->hasMany(Release::class, 'appID')->setName('releases');
        $this->hasMany(Member::class, 'appID')->setName('members');
    }

    public function validate()
    {
        Validator::addGlobalRule('app.label.unique', function ($fields, $value, $params) {
            return !self::isUniqueLabel($fields['id'] ?? null, $value);
        });

        return [
            'label' => [
                'required'         => 'Please enter a label',
                'app.label.unique' => 'The entered label is already in use'
            ]
        ];
    }

    public static function isUniqueLabel ($appID, $label)
    {
        return App::db()->from('s_application')
            ->where('label = ?', $label)
            ->where('id != ?', $appID)
            ->count() === 0;
    }

    public static function isMember($appID)
    {
        $userID = App::auth()->userID();
        $result = App::db()->from('s_application_member')
            ->where('appID', $appID)
            ->where('userID', $userID)
            ->fetch();

        return !empty($result);
    }

}