<?php

namespace ProVallo\Plugins\Savas\Models\Savas\Token;

use Favez\Mvc\ORM\Entity;

class Token extends Entity
{

    const SOURCE = 's_api_token';

    public $id;

    public $userID;

    public $label;

    public $enabled;

    public $token;

    public $created;

    public $changed;

}