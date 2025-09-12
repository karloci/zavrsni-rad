<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTime;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Exception;
use RuntimeException;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430154230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cultivation ADD start_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD end_date DATE DEFAULT NULL');

        $this->connection->beginTransaction();
        try {
            $offset = 0;
            $batchSize = 1000;

            do {
                $cultivations = $this->connection->fetchAllAssociative('SELECT id, start_year, start_month, end_year, end_month FROM cultivation LIMIT ? OFFSET ?', [$batchSize, $offset]);
                foreach ($cultivations as $cultivation) {
                    $startDate = (new DateTime())
                        ->setDate($cultivation["start_year"], $cultivation["start_month"], 1)
                        ->setTime(0, 0)
                        ->format("Y-m-d");

                    $endDate = (new DateTime())
                        ->setDate($cultivation["end_year"], $cultivation["end_month"], 1)
                        ->modify('last day of this month')
                        ->setTime(0, 0)
                        ->format("Y-m-d");

                    $this->addSql('UPDATE cultivation SET start_date = ?, end_date = ? WHERE id = ?', [$startDate, $endDate, $cultivation["id"]]);
                }
                $offset += $batchSize;
            } while (!empty($cultivations));

            $this->connection->commit();
        }
        catch (Exception $e) {
            $this->connection->rollBack();
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $this->addSql('ALTER TABLE cultivation ALTER COLUMN start_date SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN end_date SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation DROP start_year');
        $this->addSql('ALTER TABLE cultivation DROP start_month');
        $this->addSql('ALTER TABLE cultivation DROP end_year');
        $this->addSql('ALTER TABLE cultivation DROP end_month');
        $this->addSql('COMMENT ON COLUMN cultivation.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.end_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cultivation ADD start_year INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD start_month INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD end_year INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD end_month INT DEFAULT NULL');

        $this->connection->beginTransaction();
        try {
            $offset = 0;
            $batchSize = 1000;

            do {
                $cultivations = $this->connection->fetchAllAssociative('SELECT id, start_date, end_date FROM cultivation LIMIT ? OFFSET ?', [$batchSize, $offset]);
                foreach ($cultivations as $cultivation) {
                    $start = new DateTime($cultivation["start_date"]);
                    $end = new DateTime($cultivation["end_date"]);

                    $startYear = (int)$start->format("Y");
                    $startMonth = (int)$start->format("m");
                    $endYear = (int)$end->format("Y");
                    $endMonth = (int)$end->format("m");

                    $this->addSql('UPDATE cultivation SET start_year = ?, start_month = ?, end_year = ?, end_month = ? WHERE id = ?', [$startYear, $startMonth, $endYear, $endMonth, $cultivation["id"]]);
                }
                $offset += $batchSize;
            } while (!empty($cultivations));

            $this->connection->commit();
        }
        catch (Exception $e) {
            $this->connection->rollBack();
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $this->addSql('ALTER TABLE cultivation ALTER COLUMN start_year SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN start_month SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN end_year SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN end_month SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation DROP start_date');
        $this->addSql('ALTER TABLE cultivation DROP end_date');
    }
}
