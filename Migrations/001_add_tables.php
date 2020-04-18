<?php

namespace ProVallo\Plugins\Savas\Migrations;

class Migration_1 extends \ProVallo\Components\Database\Migration
{

    public function up()
    {
        $this->addSQL('
            CREATE TABLE s_platform (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `userID` INT(11) NOT NULL,
              `label` VARCHAR(255) NOT NULL
            );
            
            CREATE TABLE s_channel (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `userID` INT(11) NOT NULL,
              `label` VARCHAR(255) NOT NULL,
              `short` VARCHAR(3),
              `main` TINYINT(1) DEFAULT 0
            );
            
            CREATE TABLE s_application (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `label` VARCHAR(255) NOT NULL,
              `description` TEXT,
              `visibility` VARCHAR(32),
              `privateKey` VARCHAR(32) NOT NULL,
              `publicKey` VARCHAR(32) NOT NULL,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
            
            CREATE TABLE s_application_release (
              `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `appID` INT(11) NOT NULL,
              `channelID` INT(11) NOT NULL,
              `active` TINYINT(4) DEFAULT 0,
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
              `displayName` VARCHAR(255) NOT NULL,
              `size` INT(11) NOT NULL,
              `extension` VARCHAR(255) NOT NULL,
              `mimeType` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL,
              `changed` DATETIME NOT NULL
            );
        ');
    }

    public function down()
    {

    }

}