<?php

namespace Savas\Migrations;

use CMS\Components\Database\Migration;

class Migration_1 extends Migration
{

    public function up()
    {
        $this->addSQL('
            CREATE TABLE s_platform (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `userID` INT(11) NOT NULL,
              `label` VARCHAR(255) NOT NULL,
              `description` TEXT
            );
            
            CREATE TABLE s_channel (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `userID` INT(11) NOT NULL,
              `label` VARCHAR(255) NOT NULL,
              `description` TEXT
            );
            
            CREATE TABLE s_application (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `label` VARCHAR(255) NOT NULL,
              `description` TEXT,
              `privateKey` VARCHAR(32) NOT NULL,
              `publicKey` VARCHAR(32) NOT NULL,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
            
            CREATE TABLE s_application_release (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `appID` INT(11) NOT NULL,
              `channelID` INT(11) NOT NULL,
              `version` VARCHAR(16) NOT NULL,
              `description` TEXT,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
            
            CREATE TABLE s_application_member (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `appID` INT(11) NOT NULL,
              `userID` INT(11) NOT NULL
            );
            
            CREATE TABLE s_application_release_file (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `releaseID` INT(11) NOT NULL,
              `platformID` INT(11) NOT NULL,
              `filename` VARCHAR(255) NOT NULL,
              `size` INT(11) NOT NULL,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
        ');
    }

    public function down()
    {

    }

}