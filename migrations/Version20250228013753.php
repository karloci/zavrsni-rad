<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228013753 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE city ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE country ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE crop ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE farm ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE farm ALTER address TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE farm ALTER phone TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE farm ALTER email TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE farm ALTER website TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE field ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE field_type ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE reset_password_token ALTER token TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE soil_type ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE timezone ALTER name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE timezone ALTER code TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE "user" ALTER first_name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE "user" ALTER last_name TYPE VARCHAR(45)');
		$this->addSql('ALTER TABLE verify_email_token ALTER token TYPE VARCHAR(45)');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE crop ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE "user" ALTER first_name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE "user" ALTER last_name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE field ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE farm ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE farm ALTER address TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE farm ALTER phone TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE farm ALTER email TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE farm ALTER website TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE verify_email_token ALTER token TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE reset_password_token ALTER token TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE country ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE city ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE soil_type ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE timezone ALTER name TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE timezone ALTER code TYPE VARCHAR(255)');
		$this->addSql('ALTER TABLE field_type ALTER name TYPE VARCHAR(255)');
	}
}
