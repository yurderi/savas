<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203213257 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C96E70CF72263045 ON app (technical_name)');
        $this->addSql('ALTER TABLE app_version_file ADD CONSTRAINT FK_FF0130954BBC2705 FOREIGN KEY (version_id) REFERENCES app_version (id)');
        $this->addSql('ALTER TABLE app_version_file ADD CONSTRAINT FK_FF01309593CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_FF0130954BBC2705 ON app_version_file (version_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C96E70CF72263045 ON app');
        $this->addSql('ALTER TABLE app_version_file DROP FOREIGN KEY FK_FF0130954BBC2705');
        $this->addSql('ALTER TABLE app_version_file DROP FOREIGN KEY FK_FF01309593CB796C');
        $this->addSql('DROP INDEX IDX_FF0130954BBC2705 ON app_version_file');
    }
}
