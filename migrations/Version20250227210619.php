<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227210619 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE calendar_month (id UUID NOT NULL, year_id UUID NOT NULL, month_value INT NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_E2E21368F244E7FB ON calendar_month (month_value)');
		$this->addSql('CREATE INDEX IDX_E2E2136840C1FEA7 ON calendar_month (year_id)');
		$this->addSql('COMMENT ON COLUMN calendar_month.id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN calendar_month.year_id IS \'(DC2Type:uuid)\'');
		$this->addSql('ALTER TABLE calendar_month ADD CONSTRAINT FK_E2E2136840C1FEA7 FOREIGN KEY (year_id) REFERENCES calendar_year (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE calendar_month DROP CONSTRAINT FK_E2E2136840C1FEA7');
		$this->addSql('DROP TABLE calendar_month');
	}
}
