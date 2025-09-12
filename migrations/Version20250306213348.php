<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306213348 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE calendar_month DROP CONSTRAINT fk_e2e2136840c1fea7');
		$this->addSql('DROP TABLE calendar_month');
		$this->addSql('DROP TABLE calendar_year');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE calendar_month (id UUID NOT NULL, year_id UUID NOT NULL, month_value INT NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX unique_month ON calendar_month (year_id, month_value)');
		$this->addSql('CREATE INDEX idx_e2e2136840c1fea7 ON calendar_month (year_id)');
		$this->addSql('COMMENT ON COLUMN calendar_month.id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN calendar_month.year_id IS \'(DC2Type:uuid)\'');
		$this->addSql('CREATE TABLE calendar_year (id UUID NOT NULL, year_value INT NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX uniq_e914f063be1976e3 ON calendar_year (year_value)');
		$this->addSql('COMMENT ON COLUMN calendar_year.id IS \'(DC2Type:uuid)\'');
		$this->addSql('ALTER TABLE calendar_month ADD CONSTRAINT fk_e2e2136840c1fea7 FOREIGN KEY (year_id) REFERENCES calendar_year (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
	}
}
