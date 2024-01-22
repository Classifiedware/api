<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423125258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property_group_option ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property_group_option ADD CONSTRAINT FK_46052839727ACA70 FOREIGN KEY (parent_id) REFERENCES property_group_option (id)');
        $this->addSql('CREATE INDEX IDX_46052839727ACA70 ON property_group_option (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property_group_option DROP FOREIGN KEY FK_46052839727ACA70');
        $this->addSql('DROP INDEX IDX_46052839727ACA70 ON property_group_option');
        $this->addSql('ALTER TABLE property_group_option DROP parent_id');
    }
}
