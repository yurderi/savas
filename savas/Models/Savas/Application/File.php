<?php

namespace savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;
use savas\Models\Savas\Platform;

class File extends Entity
{

    const SOURCE = 's_application_release_file';

    const SHOULD_REMOVE_WITH_PARENT = true;

    public $id;

    public $releaseID;

    public $platformID;

    public $filename;

    public $originalFilename;

    public $size;

    public $created;

    public $changed;

    public function initialize()
    {
        $this->belongsTo(Release::class, 'releaseID')->setName('release');
        $this->hasOne(Platform::class, 'platformID')->setName('platform');
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
            'originalFilename' => [
                'required' => 'Please enter a filename'
            ]
        ];
    }

}