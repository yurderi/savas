<?php

namespace ProVallo\Plugins\Savas\Migrations;

class Migration_5 extends \ProVallo\Components\Database\Migration
{

    public function up()
    {
        $this->addSQL('
            CREATE TABLE s_application_release_file_requirement (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `fileID` INT(11) NOT NULL,
              `type` VARCHAR(255) NOT NULL,
              `name` VARCHAR(255) NOT NULL,
              `version` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
        ');
    }

    public function down()
    {

    }

}