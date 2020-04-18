<?php

namespace ProVallo\Plugins\Savas\Migrations;

class Migration_3 extends \ProVallo\Components\Database\Migration
{

    public function up()
    {
        $this->addSQL('
            ALTER TABLE s_application_release_file
              ADD COLUMN `systemRequirements` BLOB AFTER `mimeType`;
        ');
    }

    public function down()
    {

    }

}