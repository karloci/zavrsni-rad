<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212230933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE season (id UUID NOT NULL, farm_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, deleted_by UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0E45BA965FCFA0D ON season (farm_id)');
        $this->addSql('CREATE INDEX IDX_F0E45BA9DE12AB56 ON season (created_by)');
        $this->addSql('CREATE INDEX IDX_F0E45BA916FE72E1 ON season (updated_by)');
        $this->addSql('CREATE INDEX IDX_F0E45BA91F6FA0AF ON season (deleted_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQUE_SEASON ON season (farm_id, name)');
        $this->addSql('COMMENT ON COLUMN season.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN season.farm_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN season.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN season.updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN season.deleted_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN season.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN season.end_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN season.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN season.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN season.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA965FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA916FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA91F6FA0AF FOREIGN KEY (deleted_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE city ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN city.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE country ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN country.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE crop ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN crop.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE farm ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE farm ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE farm ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN farm.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN farm.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN farm.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE timezone ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN timezone.deleted_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA965FCFA0D');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA9DE12AB56');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA916FE72E1');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA91F6FA0AF');
        $this->addSql('DROP TABLE season');
        $this->addSql('ALTER TABLE crop ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN crop.deleted_at IS NULL');
        $this->addSql('ALTER TABLE country ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN country.deleted_at IS NULL');
        $this->addSql('ALTER TABLE farm ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE farm ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE farm ALTER deleted_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('COMMENT ON COLUMN farm.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN farm.updated_at IS NULL');
        $this->addSql('COMMENT ON COLUMN farm.deleted_at IS NULL');
        $this->addSql('ALTER TABLE timezone ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN timezone.deleted_at IS NULL');
        $this->addSql('ALTER TABLE city ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN city.deleted_at IS NULL');
    }
}
