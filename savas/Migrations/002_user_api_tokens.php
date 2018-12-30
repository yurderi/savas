<?php

namespace ProVallo\Plugins\Savas\Migrations;

class Migration_2 extends \ProVallo\Components\Database\Migration
{

    public function up()
    {
        $this->addSQL('
            CREATE TABLE s_api_token (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `userID` INT(11) NOT NULL,
              `label` VARCHAR(255) NOT NULL,
              `enabled` TINYINT(1) DEFAULT 0,
              `token` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
            
            CREATE TABLE s_api_log (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `tokenID` INT(11) NOT NULL,
              `data` LONGTEXT,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
        ');
    }

    public function down()
    {

    }

}