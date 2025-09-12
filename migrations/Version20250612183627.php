<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Exception;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612183627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crop_rotation (id UUID NOT NULL, cultivation_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, planting_date DATE DEFAULT NULL, harvest_date DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0ECC1203932FFBE ON crop_rotation (cultivation_id)');
        $this->addSql('CREATE INDEX IDX_A0ECC120DE12AB56 ON crop_rotation (created_by)');
        $this->addSql('CREATE INDEX IDX_A0ECC12016FE72E1 ON crop_rotation (updated_by)');
        $this->addSql('COMMENT ON COLUMN crop_rotation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.cultivation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.planting_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.harvest_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC1203932FFBE FOREIGN KEY (cultivation_id) REFERENCES cultivation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC120DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC12016FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->connection->beginTransaction();
        try {
            $offset = 0;
            $batchSize = 1000;

            do {
                $cultivations = $this->connection->fetchAllAssociative('SELECT * FROM cultivation WHERE season_id IS NOT NULL LIMIT ? OFFSET ?', [$batchSize, $offset]);
                foreach ($cultivations as $cultivation) {
                    $this->addSql('INSERT INTO crop_rotation (id, cultivation_id, planting_date, harvest_date, created_by, updated_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [Uuid::v4()->toRfc4122(), $cultivation["id"], $cultivation["planting_date"], $cultivation["harvest_date"], $cultivation["created_by"], $cultivation["updated_by"], $cultivation["created_at"], $cultivation["updated_at"]]);
                }
                $offset += $batchSize;
            } while (!empty($cultivations));

            $this->connection->commit();
        }
        catch (Exception $e) {
            $this->connection->rollBack();
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $this->addSql('ALTER TABLE cultivation DROP CONSTRAINT fk_acafa797de12ab56');
        $this->addSql('ALTER TABLE cultivation DROP CONSTRAINT fk_acafa79716fe72e1');
        $this->addSql('DROP INDEX idx_acafa79716fe72e1');
        $this->addSql('DROP INDEX idx_acafa797de12ab56');
        $this->addSql('ALTER TABLE cultivation DROP created_by');
        $this->addSql('ALTER TABLE cultivation DROP updated_by');
        $this->addSql('ALTER TABLE cultivation DROP created_at');
        $this->addSql('ALTER TABLE cultivation DROP updated_at');
        $this->addSql('ALTER TABLE cultivation DROP planting_date');
        $this->addSql('ALTER TABLE cultivation DROP harvest_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cultivation ADD created_by UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD updated_by UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD planting_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE cultivation ADD harvest_date DATE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN cultivation.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.planting_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.harvest_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE cultivation ADD CONSTRAINT fk_acafa797de12ab56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cultivation ADD CONSTRAINT fk_acafa79716fe72e1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_acafa79716fe72e1 ON cultivation (updated_by)');
        $this->addSql('CREATE INDEX idx_acafa797de12ab56 ON cultivation (created_by)');

        $this->connection->beginTransaction();
        try {
            $offset = 0;
            $batchSize = 1000;

            do {
                $cropRotations = $this->connection->fetchAllAssociative('SELECT * FROM crop_rotation LIMIT ? OFFSET ?', [$batchSize, $offset]);
                foreach ($cropRotations as $cropRotation) {
                    $this->addSql('UPDATE cultivation SET planting_date=?, harvest_date=?, created_by=?, updated_by=?, created_at=?, updated_at=? WHERE id = ?', [$cropRotation["planting_date"], $cropRotation["harvest_date"], $cropRotation["created_by"], $cropRotation["updated_by"], $cropRotation["created_at"], $cropRotation["updated_at"], $cropRotation["cultivation_id"]]);
                }
                $offset += $batchSize;
            } while (!empty($cropRotations));

            $this->connection->commit();
        }
        catch (Exception $e) {
            $this->connection->rollBack();
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $this->addSql('ALTER TABLE cultivation ALTER COLUMN created_by SET NOT NULL');
        $this->addSql('ALTER TABLE cultivation ALTER COLUMN created_at SET NOT NULL');
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC1203932FFBE');
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC120DE12AB56');
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC12016FE72E1');
        $this->addSql('DROP TABLE crop_rotation');
    }
}
