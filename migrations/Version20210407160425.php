<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407160425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource ADD latest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416C32E1D1C FOREIGN KEY (latest_id) REFERENCES patch_note (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC91F416C32E1D1C ON resource (latest_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416C32E1D1C');
        $this->addSql('DROP INDEX UNIQ_BC91F416C32E1D1C ON resource');
        $this->addSql('ALTER TABLE resource DROP latest_id');
    }
}
