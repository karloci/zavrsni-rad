<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307125852 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE cultivation (id UUID NOT NULL, field_id UUID NOT NULL, crop_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, start_year INT NOT NULL, start_month INT NOT NULL, end_year INT NOT NULL, end_month INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX IDX_ACAFA797443707B0 ON cultivation (field_id)');
		$this->addSql('CREATE INDEX IDX_ACAFA797888579EE ON cultivation (crop_id)');
		$this->addSql('CREATE INDEX IDX_ACAFA797DE12AB56 ON cultivation (created_by)');
		$this->addSql('CREATE INDEX IDX_ACAFA79716FE72E1 ON cultivation (updated_by)');
		$this->addSql('COMMENT ON COLUMN cultivation.id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN cultivation.field_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN cultivation.crop_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN cultivation.created_by IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN cultivation.updated_by IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN cultivation.created_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('COMMENT ON COLUMN cultivation.updated_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('ALTER TABLE cultivation ADD CONSTRAINT FK_ACAFA797443707B0 FOREIGN KEY (field_id) REFERENCES field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE cultivation ADD CONSTRAINT FK_ACAFA797888579EE FOREIGN KEY (crop_id) REFERENCES crop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE cultivation ADD CONSTRAINT FK_ACAFA797DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE cultivation ADD CONSTRAINT FK_ACAFA79716FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT fk_a0ecc120443707b0');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT fk_a0ecc120888579ee');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT fk_a0ecc120de12ab56');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT fk_a0ecc12016fe72e1');
		$this->addSql('DROP TABLE crop_rotation');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE crop_rotation (id UUID NOT NULL, field_id UUID NOT NULL, crop_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, start_year INT NOT NULL, start_month INT NOT NULL, end_year INT NOT NULL, end_month INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX idx_a0ecc12016fe72e1 ON crop_rotation (updated_by)');
		$this->addSql('CREATE INDEX idx_a0ecc120de12ab56 ON crop_rotation (created_by)');
		$this->addSql('CREATE INDEX idx_a0ecc120888579ee ON crop_rotation (crop_id)');
		$this->addSql('CREATE INDEX idx_a0ecc120443707b0 ON crop_rotation (field_id)');
		$this->addSql('COMMENT ON COLUMN crop_rotation.id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.field_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.crop_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.created_by IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.updated_by IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.created_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.updated_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT fk_a0ecc120443707b0 FOREIGN KEY (field_id) REFERENCES field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT fk_a0ecc120888579ee FOREIGN KEY (crop_id) REFERENCES crop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT fk_a0ecc120de12ab56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT fk_a0ecc12016fe72e1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE cultivation DROP CONSTRAINT FK_ACAFA797443707B0');
		$this->addSql('ALTER TABLE cultivation DROP CONSTRAINT FK_ACAFA797888579EE');
		$this->addSql('ALTER TABLE cultivation DROP CONSTRAINT FK_ACAFA797DE12AB56');
		$this->addSql('ALTER TABLE cultivation DROP CONSTRAINT FK_ACAFA79716FE72E1');
		$this->addSql('DROP TABLE cultivation');
	}
}
