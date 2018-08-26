<?php

namespace savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;

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
    }

    public function validate()
    {
        return [
            'label' => [
                'required' => 'Please enter a label'
            ]
        ];
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