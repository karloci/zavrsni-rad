<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219205409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE field (id UUID NOT NULL, farm_id UUID NOT NULL, field_type_id UUID NOT NULL, soil_type_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, deleted_by UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, area DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5BF5455865FCFA0D ON field (farm_id)');
        $this->addSql('CREATE INDEX IDX_5BF545582B68A933 ON field (field_type_id)');
        $this->addSql('CREATE INDEX IDX_5BF54558A8AE1818 ON field (soil_type_id)');
        $this->addSql('CREATE INDEX IDX_5BF54558DE12AB56 ON field (created_by)');
        $this->addSql('CREATE INDEX IDX_5BF5455816FE72E1 ON field (updated_by)');
        $this->addSql('CREATE INDEX IDX_5BF545581F6FA0AF ON field (deleted_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQUE_FIELD ON field (farm_id, name)');
        $this->addSql('COMMENT ON COLUMN field.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.farm_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.field_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.soil_type_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.deleted_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN field.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN field.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN field.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF5455865FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF545582B68A933 FOREIGN KEY (field_type_id) REFERENCES field_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF54558A8AE1818 FOREIGN KEY (soil_type_id) REFERENCES soil_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF54558DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF5455816FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF545581F6FA0AF FOREIGN KEY (deleted_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE field DROP CONSTRAINT FK_5BF5455865FCFA0D');
        $this->addSql('ALTER TABLE field DROP CONSTRAINT FK_5BF545582B68A933');
        $this->addSql('ALTER TABLE field DROP CONSTRAINT FK_5BF54558A8AE1818');
        $this->addSql('ALTER TABLE field DROP CONSTRAINT FK_5BF54558DE12AB56');
        $this->addSql('ALTER TABLE field DROP CONSTRAINT FK_5BF5455816FE72E1');
        $this->addSql('ALTER TABLE field DROP CONSTRAINT FK_5BF545581F6FA0AF');
        $this->addSql('DROP TABLE field');
    }
}
