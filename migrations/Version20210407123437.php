<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407123437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patch_note (id INT AUTO_INCREMENT NOT NULL, resource_id INT NOT NULL, version VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, link VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, publish_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_7C82413E89329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patch_note ADD CONSTRAINT FK_7C82413E89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE resource ADD latest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416C32E1D1C FOREIGN KEY (latest_id) REFERENCES patch_note (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC91F416C32E1D1C ON resource (latest_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416C32E1D1C');
        $this->addSql('DROP TABLE patch_note');
        $this->addSql('DROP INDEX UNIQ_BC91F416C32E1D1C ON resource');
        $this->addSql('ALTER TABLE resource DROP latest_id');
    }
}
