<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225190956 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE season DROP CONSTRAINT fk_f0e45ba965fcfa0d');
		$this->addSql('ALTER TABLE season DROP CONSTRAINT fk_f0e45ba9de12ab56');
		$this->addSql('ALTER TABLE season DROP CONSTRAINT fk_f0e45ba916fe72e1');
		$this->addSql('ALTER TABLE season DROP CONSTRAINT fk_f0e45ba91f6fa0af');
		$this->addSql('DROP TABLE season');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE season (id UUID NOT NULL, farm_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, deleted_by UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX unique_season ON season (farm_id, name)');
		$this->addSql('CREATE INDEX idx_f0e45ba91f6fa0af ON season (deleted_by)');
		$this->addSql('CREATE INDEX idx_f0e45ba916fe72e1 ON season (updated_by)');
		$this->addSql('CREATE INDEX idx_f0e45ba9de12ab56 ON season (created_by)');
		$this->addSql('CREATE INDEX idx_f0e45ba965fcfa0d ON season (farm_id)');
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
		$this->addSql('ALTER TABLE season ADD CONSTRAINT fk_f0e45ba965fcfa0d FOREIGN KEY (farm_id) REFERENCES farm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE season ADD CONSTRAINT fk_f0e45ba9de12ab56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE season ADD CONSTRAINT fk_f0e45ba916fe72e1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE season ADD CONSTRAINT fk_f0e45ba91f6fa0af FOREIGN KEY (deleted_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
	}
}
