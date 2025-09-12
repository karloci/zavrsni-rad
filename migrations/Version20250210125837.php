<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210125837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timezone (id UUID NOT NULL, country_id UUID NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3701B2975E237E06 ON timezone (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3701B29777153098 ON timezone (code)');
        $this->addSql('CREATE INDEX IDX_3701B297F92F3E70 ON timezone (country_id)');
        $this->addSql('COMMENT ON COLUMN timezone.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN timezone.country_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE timezone ADD CONSTRAINT FK_3701B297F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timezone DROP CONSTRAINT FK_3701B297F92F3E70');
        $this->addSql('DROP TABLE timezone');
    }
}
