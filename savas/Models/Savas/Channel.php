<?php

namespace savas\Models\Savas;

use Favez\Mvc\ORM\Entity;

class Channel extends Entity
{

    const SOURCE = 's_channel';

    public $id;

    public $userID;

    public $label;

    public $description;

}