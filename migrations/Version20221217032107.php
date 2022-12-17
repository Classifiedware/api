<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221217032107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classified_property_group_option_value (id INT AUTO_INCREMENT NOT NULL, classified_id INT DEFAULT NULL, property_group_option_value_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A64533CA0B0417E (classified_id), INDEX IDX_5A64533CF4D8377E (property_group_option_value_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classified_property_group_option_value ADD CONSTRAINT FK_5A64533CA0B0417E FOREIGN KEY (classified_id) REFERENCES classified (id)');
        $this->addSql('ALTER TABLE classified_property_group_option_value ADD CONSTRAINT FK_5A64533CF4D8377E FOREIGN KEY (property_group_option_value_id) REFERENCES property_group_option_value (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classified_property_group_option_value DROP FOREIGN KEY FK_5A64533CA0B0417E');
        $this->addSql('ALTER TABLE classified_property_group_option_value DROP FOREIGN KEY FK_5A64533CF4D8377E');
        $this->addSql('DROP TABLE classified_property_group_option_value');
    }
}
