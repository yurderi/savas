<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202201505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_version_file (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', platform_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', version_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', file_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', created DATETIME NOT NULL, changed DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FF01309593CB796C (file_id), INDEX IDX_FF013095FFE6496F (platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, size INT NOT NULL, path VARCHAR(255) NOT NULL, created DATETIME NOT NULL, changed DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', technical_name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, created DATETIME NOT NULL, changed DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_version_file ADD CONSTRAINT FK_FF013095FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('ALTER TABLE app_version ADD CONSTRAINT FK_5241538E7987212D FOREIGN KEY (app_id) REFERENCES app (id)');
        $this->addSql('CREATE INDEX IDX_5241538E7987212D ON app_version (app_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_version_file DROP FOREIGN KEY FK_FF013095FFE6496F');
        $this->addSql('DROP TABLE app_version_file');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE platform');
        $this->addSql('ALTER TABLE app_version DROP FOREIGN KEY FK_5241538E7987212D');
        $this->addSql('DROP INDEX IDX_5241538E7987212D ON app_version');
    }
}
