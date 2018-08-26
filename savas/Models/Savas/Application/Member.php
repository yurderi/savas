<?php

namespace savas\Models\Savas\Application;

use Favez\Mvc\ORM\Entity;

class Member extends Entity
{

    const SOURCE = 's_application_member';

    const SHOULD_REMOVE_WITH_PARENT = true;

    public $id;

    public $appID;

    public $userID;

}