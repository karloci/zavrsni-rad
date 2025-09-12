<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Exception;
use RuntimeException;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228141012 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE farm ADD country_id UUID DEFAULT NULL');
		$this->addSql('COMMENT ON COLUMN farm.country_id IS \'(DC2Type:uuid)\'');

		$this->connection->beginTransaction();
		try {
			$offset = 0;
			$batchSize = 1000;

			do {
				$farms = $this->connection->fetchAllAssociative('SELECT id, city_id FROM farm LIMIT ? OFFSET ?', [$batchSize, $offset]);
				foreach ($farms as $farm) {
					$farmCity = $this->connection->fetchAssociative('SELECT country_id FROM city WHERE id = ?', [$farm["city_id"]]);

					if ($farmCity) {
						$this->addSql('UPDATE farm SET country_id = ? WHERE id = ?', [$farmCity["country_id"], $farm["id"]]);
					}
				}
				$offset += $batchSize;
			} while (!empty($farms));

			$this->connection->commit();
		}
		catch (Exception $e) {
			$this->connection->rollBack();
			throw new RuntimeException($e->getMessage(), 0, $e);
		}

		$this->addSql('ALTER TABLE farm ALTER COLUMN country_id SET NOT NULL');
		$this->addSql('ALTER TABLE farm ADD CONSTRAINT FK_5816D045F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('CREATE INDEX IDX_5816D045F92F3E70 ON farm (country_id)');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE farm DROP CONSTRAINT FK_5816D045F92F3E70');
		$this->addSql('DROP INDEX IDX_5816D045F92F3E70');
		$this->addSql('ALTER TABLE farm DROP country_id');
	}
}
