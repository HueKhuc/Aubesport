<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230920100408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD address_uuid VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AC33804D FOREIGN KEY (address_uuid) REFERENCES address (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AC33804D ON user (address_uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AC33804D');
        $this->addSql('DROP INDEX UNIQ_8D93D649AC33804D ON user');
        $this->addSql('ALTER TABLE user DROP address_uuid');
    }
}
