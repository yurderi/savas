<?php

namespace ProVallo\Plugins\Savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;
use Validator\Validator;

class Application extends Entity
{

    const SOURCE = 's_application';

    public $id;

    public $label;

    public $description;

    public $visibility;

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
            $appID = App::request()->getParam('id');

            return self::isUniqueLabel($appID, $value);
        });

        return [
            'label' => [
                'required'         => 'Please enter a label',
                'app.label.unique' => 'The entered label is already in use'
            ],
            'visibility' => [
                'required'          => 'Please select a visibility',
                'in:public,private' => 'The visibility must be public or private.'
            ]
        ];
    }

    public static function isUniqueLabel ($appID, $label)
    {
        $query = App::db()->from('s_application')
            ->where('label = ?', $label);

        if ($appID > 0)
        {
            $query->where('id != ?', $appID);
        }

        return $query->count() === 0;
    }

    public static function isMember($appID)
    {
        $userID = App::auth()->getUserID();
        $result = App::db()->from('s_application_member')
            ->where('appID', $appID)
            ->where('userID', $userID)
            ->fetch();

        return !empty($result);
    }

}