<?php

namespace ProVallo\Plugins\Savas\Models\Savas;

use Favez\Mvc\ORM\Entity;

class Platform extends Entity
{

    const SOURCE = 's_platform';

    public $id;

    public $userID;

    public $label;

    public function validate()
    {
        return [
            'label' => [
                'required' => 'Please enter a label'
            ]
        ];
    }

}