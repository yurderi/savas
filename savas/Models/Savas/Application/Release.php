<?php

namespace savas\Models\Savas\Application;

use Favez\Mvc\ORM\Entity;

class Release extends Entity
{

    const SOURCE = 's_application_release';

    const SHOULD_REMOVE_WITH_PARENT = true;

    public $id;

    public $appID;

    public $channelID;

    public $version;

    public $description;

    public $created;

    public $changed;

    public function initialize()
    {
        $this->belongsTo(Application::class, 'appID')->setName('application');
        $this->hasMany(File::class, 'releaseID')->setName('files');
    }

    public function validate()
    {
        return [
            'appID' => [
                'required' => 'Please select an application'
            ],
            'channelID' => [
                'required' => 'Please select a channel'
            ],
            'version' => [
                'required' => 'Please enter a version'
            ]
        ];
    }

}