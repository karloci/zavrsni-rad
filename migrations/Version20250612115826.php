<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612115826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cultivation RENAME COLUMN start_date TO planting_date');
        $this->addSql('ALTER TABLE cultivation RENAME COLUMN end_date TO harvest_date');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN planting_date DROP NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN harvest_date DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN cultivation.planting_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.harvest_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cultivation RENAME COLUMN planting_date TO start_date');
        $this->addSql('ALTER TABLE cultivation RENAME COLUMN harvest_date TO end_date');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN start_date SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN end_date SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN cultivation.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.end_date IS \'(DC2Type:date_immutable)\'');
    }
}
