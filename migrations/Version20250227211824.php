<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227211824 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP INDEX uniq_e2e21368f244e7fb');
		$this->addSql('CREATE UNIQUE INDEX UNIQUE_MONTH ON calendar_month (year_id, month_value)');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP INDEX UNIQUE_MONTH');
		$this->addSql('CREATE UNIQUE INDEX uniq_e2e21368f244e7fb ON calendar_month (month_value)');
	}
}
