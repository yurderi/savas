<?php

namespace ProVallo\Plugins\Savas\Models\Savas\Application;

use Favez\Mvc\App;
use Favez\Mvc\ORM\Entity;
use ProVallo\Plugins\Savas\Models\Savas\Platform;

class Requirement extends Entity
{

    const SOURCE = 's_application_release_file_requirement';

    const SHOULD_REMOVE_WITH_PARENT = true;

    public $id;

    public $fileID;

    public $type;

    public $name;

    public $version;

    public $created;

    public $changed;

    public function initialize()
    {
    }

}