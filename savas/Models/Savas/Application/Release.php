<?php

namespace savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;
use Validator\Validator;

class Release extends Entity
{

    const SOURCE = 's_application_release';

    const SHOULD_REMOVE_WITH_PARENT = true;

    public $id;

    public $appID;

    public $channelID;

    public $active;

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
        Validator::addGlobalRule('release.channel.exists', function ($fields, $value, $params) {
            $id = App::db()->from('s_channel')->where('id = ?', $value)->fetchColumn();

            return !empty($id);
        });

        Validator::addGlobalRule('release.version.unique', function ($fields, $value, $params) {
            $query = App::db()->from('s_application_release')
                ->where('version LIKE ?', $value)
                ->where('channelID = ?', $fields['channelID']['value'])
                ->where('appID = ?', $fields['appID']['value']);

            if ($id = App::request()->getParam('id'))
            {
                $query->where('id != ?', $id);
            }

            $id = (int) $query->fetchColumn(0);

            return $id === 0;
        });

        return [
            'appID' => [
                'required' => 'Please select an application'
            ],
            'channelID' => [
                'required' => 'Please select a channel',
                'release.channel.exists' => 'The provided channel does not exists'
            ],
            'version' => [
                'required' => 'Please enter a version',
                'release.version.unique' => 'The provided version is already used'
            ]
        ];
    }

}