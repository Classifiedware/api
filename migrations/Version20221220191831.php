<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220191831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classified_property_group_option_value MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON classified_property_group_option_value');
        $this->addSql('ALTER TABLE classified_property_group_option_value DROP id, DROP created_at, DROP updated_at, CHANGE classified_id classified_id INT NOT NULL, CHANGE property_group_option_value_id property_group_option_value_id INT NOT NULL');
        $this->addSql('ALTER TABLE classified_property_group_option_value ADD PRIMARY KEY (classified_id, property_group_option_value_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classified_property_group_option_value ADD id INT AUTO_INCREMENT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE classified_id classified_id INT DEFAULT NULL, CHANGE property_group_option_value_id property_group_option_value_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
