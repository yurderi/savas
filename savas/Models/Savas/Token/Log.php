<?php

namespace savas\Models\Savas\Token;

use Favez\Mvc\ORM\Entity;

class Log extends Entity
{

    const SOURCE = 's_api_log';

    public $id;

    public $tokenID;

    public $data;

    public $created;

    public $changed;

}