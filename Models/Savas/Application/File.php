<?php

namespace ProVallo\Plugins\Savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;
use ProVallo\Plugins\Savas\Models\Savas\Platform;

class File extends Entity
{

    const SOURCE = 's_application_release_file';

    const SHOULD_REMOVE_WITH_PARENT = true;

    public $id;

    public $releaseID;

    public $platformID;

    public $filename;

    public $displayName;

    public $size;

    public $extension;

    public $mimeType;

    public $created;

    public $changed;

    public function initialize()
    {
        $this->belongsTo(Release::class, 'releaseID')->setName('release');
        $this->hasOne(Platform::class, 'platformID')->setName('platform');
        $this->hasMany(Requirement::class, 'fileID')->setName('requirements');
    }

    public function onRemove()
    {
        $directory = App::path() . '/media/savas/';
        $filename  = $directory . $this->filename;

        if (is_file($filename))
        {
            unlink($filename);
        }
    }

    public function validate()
    {
        return [
            'releaseID' => [
                'required' => 'Please select a release'
            ],
            'platformID' => [
                'required' => 'Please select a platform'
            ],
            'displayName' => [
                'required' => 'Please enter a filename'
            ]
        ];
    }

}