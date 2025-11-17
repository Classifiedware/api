<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251117130651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD file_hash VARCHAR(255) NOT NULL, ADD width INT NOT NULL, ADD height INT NOT NULL, ADD file_name VARCHAR(255) NOT NULL, ADD file_size INT NOT NULL, ADD file_extension VARCHAR(10) NOT NULL, ADD mime_type VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP file_hash, DROP width, DROP height, DROP file_name, DROP file_size, DROP file_extension, DROP mime_type');
    }
}
