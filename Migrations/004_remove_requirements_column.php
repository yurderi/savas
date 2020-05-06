<?php

namespace ProVallo\Plugins\Savas\Migrations;

class Migration_4 extends \ProVallo\Components\Database\Migration
{

    public function up()
    {
        $this->addSQL('
            ALTER TABLE s_application_release_file
              DROP COLUMN `systemRequirements`;
        ');
    }

    public function down()
    {

    }

}