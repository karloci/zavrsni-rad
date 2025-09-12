<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612114608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE season (id UUID NOT NULL, farm_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, deleted_by UUID DEFAULT NULL, name VARCHAR(45) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
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
        $this->addSql('ALTER TABLE cultivation ADD season_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN cultivation.season_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE cultivation ADD CONSTRAINT FK_ACAFA7974EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_ACAFA7974EC001D1 ON cultivation (season_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQUE_CULTIVATION ON cultivation (season_id, field_id, crop_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cultivation DROP CONSTRAINT FK_ACAFA7974EC001D1');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA965FCFA0D');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA9DE12AB56');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA916FE72E1');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA91F6FA0AF');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP INDEX IDX_ACAFA7974EC001D1');
        $this->addSql('DROP INDEX UNIQUE_CULTIVATION');
        $this->addSql('ALTER TABLE cultivation DROP season_id');
    }
}
