<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306215521 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE crop_rotation (id UUID NOT NULL, field_id UUID NOT NULL, crop_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, start_year INT NOT NULL, start_month INT NOT NULL, end_year INT NOT NULL, end_month INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX IDX_A0ECC120443707B0 ON crop_rotation (field_id)');
		$this->addSql('CREATE INDEX IDX_A0ECC120888579EE ON crop_rotation (crop_id)');
		$this->addSql('CREATE INDEX IDX_A0ECC120DE12AB56 ON crop_rotation (created_by)');
		$this->addSql('CREATE INDEX IDX_A0ECC12016FE72E1 ON crop_rotation (updated_by)');
		$this->addSql('COMMENT ON COLUMN crop_rotation.id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.field_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.crop_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.created_by IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.updated_by IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.created_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('COMMENT ON COLUMN crop_rotation.updated_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC120443707B0 FOREIGN KEY (field_id) REFERENCES field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC120888579EE FOREIGN KEY (crop_id) REFERENCES crop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC120DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC12016FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC120443707B0');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC120888579EE');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC120DE12AB56');
		$this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC12016FE72E1');
		$this->addSql('DROP TABLE crop_rotation');
	}
}
