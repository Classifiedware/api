<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605220621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classified_media (id INT AUTO_INCREMENT NOT NULL, classified_id INT DEFAULT NULL, media_id INT DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F457259A0B0417E (classified_id), INDEX IDX_F457259EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_thumbnail (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, width VARCHAR(255) NOT NULL, height VARCHAR(255) NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E6379568EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classified_media ADD CONSTRAINT FK_F457259A0B0417E FOREIGN KEY (classified_id) REFERENCES classified (id)');
        $this->addSql('ALTER TABLE classified_media ADD CONSTRAINT FK_F457259EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE media_thumbnail ADD CONSTRAINT FK_E6379568EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classified_media DROP FOREIGN KEY FK_F457259A0B0417E');
        $this->addSql('ALTER TABLE classified_media DROP FOREIGN KEY FK_F457259EA9FDD75');
        $this->addSql('ALTER TABLE media_thumbnail DROP FOREIGN KEY FK_E6379568EA9FDD75');
        $this->addSql('DROP TABLE classified_media');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_thumbnail');
    }
}
