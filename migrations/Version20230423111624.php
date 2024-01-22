<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423111624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classified_property_group_option (classified_id INT NOT NULL, property_group_option_id INT NOT NULL, INDEX IDX_14315A9FA0B0417E (classified_id), INDEX IDX_14315A9F60CC414C (property_group_option_id), PRIMARY KEY(classified_id, property_group_option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classified_property_group_option ADD CONSTRAINT FK_14315A9FA0B0417E FOREIGN KEY (classified_id) REFERENCES classified (id)');
        $this->addSql('ALTER TABLE classified_property_group_option ADD CONSTRAINT FK_14315A9F60CC414C FOREIGN KEY (property_group_option_id) REFERENCES property_group_option (id)');
        $this->addSql('ALTER TABLE classified_property_group_option_value DROP FOREIGN KEY FK_5A64533CA0B0417E');
        $this->addSql('ALTER TABLE classified_property_group_option_value DROP FOREIGN KEY FK_5A64533CF4D8377E');
        $this->addSql('ALTER TABLE property_group_option_value DROP FOREIGN KEY FK_64287486777418E');
        $this->addSql('DROP TABLE classified_property_group_option_value');
        $this->addSql('DROP TABLE property_group_option_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classified_property_group_option_value (classified_id INT NOT NULL, property_group_option_value_id INT NOT NULL, INDEX IDX_5A64533CF4D8377E (property_group_option_value_id), INDEX IDX_5A64533CA0B0417E (classified_id), PRIMARY KEY(classified_id, property_group_option_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE property_group_option_value (id INT AUTO_INCREMENT NOT NULL, group_option_id INT DEFAULT NULL, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', show_in_search_page TINYINT(1) NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_64287486777418E (group_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE classified_property_group_option_value ADD CONSTRAINT FK_5A64533CA0B0417E FOREIGN KEY (classified_id) REFERENCES classified (id)');
        $this->addSql('ALTER TABLE classified_property_group_option_value ADD CONSTRAINT FK_5A64533CF4D8377E FOREIGN KEY (property_group_option_value_id) REFERENCES property_group_option_value (id)');
        $this->addSql('ALTER TABLE property_group_option_value ADD CONSTRAINT FK_64287486777418E FOREIGN KEY (group_option_id) REFERENCES property_group_option (id)');
        $this->addSql('ALTER TABLE classified_property_group_option DROP FOREIGN KEY FK_14315A9FA0B0417E');
        $this->addSql('ALTER TABLE classified_property_group_option DROP FOREIGN KEY FK_14315A9F60CC414C');
        $this->addSql('DROP TABLE classified_property_group_option');
    }
}
