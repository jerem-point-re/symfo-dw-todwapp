<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025193911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lists ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597D4619D1A');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597D4619D1A FOREIGN KEY (lists_id) REFERENCES lists (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lists DROP image_name');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597D4619D1A');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597D4619D1A FOREIGN KEY (lists_id) REFERENCES lists (id)');
    }
}
