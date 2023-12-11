<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210111928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, menu_superieur_id INT DEFAULT NULL, sous_titre VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, url VARCHAR(4000) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, icon LONGTEXT DEFAULT NULL, type_menu VARCHAR(2) DEFAULT NULL, roles VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_7D053A93F9ADC1B4 (menu_superieur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93F9ADC1B4 FOREIGN KEY (menu_superieur_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93F9ADC1B4');
        $this->addSql('DROP TABLE menu');
    }
}
