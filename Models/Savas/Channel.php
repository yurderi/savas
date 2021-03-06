<?php

namespace ProVallo\Plugins\Savas\Models\Savas;

use Favez\Mvc\ORM\Entity;

class Channel extends Entity
{

    const SOURCE = 's_channel';

    public $id;

    public $userID;

    public $label;

    public $short;

    public $main;

    public function validate()
    {
        return [
            'label' => [
                'required' => 'Please enter a label'
            ]
        ];
    }

}