<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020212451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament (uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, starting_date DATE NOT NULL, ending_date DATE DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_registration (uuid VARCHAR(255) NOT NULL, tournament_id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F42ADBF133D1A3E7 (tournament_id), INDEX IDX_F42ADBF1A76ED395 (user_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_registration ADD CONSTRAINT FK_F42ADBF133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (uuid)');
        $this->addSql('ALTER TABLE tournament_registration ADD CONSTRAINT FK_F42ADBF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_registration DROP FOREIGN KEY FK_F42ADBF133D1A3E7');
        $this->addSql('ALTER TABLE tournament_registration DROP FOREIGN KEY FK_F42ADBF1A76ED395');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_registration');
    }
}
