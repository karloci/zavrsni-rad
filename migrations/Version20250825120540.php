<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825120540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT fk_a0ecc1203932ffbe');
        $this->addSql('ALTER TABLE cultivation DROP CONSTRAINT fk_acafa797443707b0');
        $this->addSql('ALTER TABLE cultivation DROP CONSTRAINT fk_acafa797888579ee');
        $this->addSql('ALTER TABLE cultivation DROP CONSTRAINT fk_acafa7974ec001d1');
        $this->addSql('DROP TABLE cultivation');
        $this->addSql('DROP INDEX uniq_a0ecc1203932ffbe');
        $this->addSql('ALTER TABLE crop_rotation ADD season_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE crop_rotation ADD crop_id UUID NOT NULL');
        $this->addSql('ALTER TABLE crop_rotation RENAME COLUMN cultivation_id TO field_id');
        $this->addSql('COMMENT ON COLUMN crop_rotation.season_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN crop_rotation.crop_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC1204EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC120443707B0 FOREIGN KEY (field_id) REFERENCES field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT FK_A0ECC120888579EE FOREIGN KEY (crop_id) REFERENCES crop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A0ECC1204EC001D1 ON crop_rotation (season_id)');
        $this->addSql('CREATE INDEX IDX_A0ECC120443707B0 ON crop_rotation (field_id)');
        $this->addSql('CREATE INDEX IDX_A0ECC120888579EE ON crop_rotation (crop_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cultivation (id UUID NOT NULL, field_id UUID NOT NULL, crop_id UUID NOT NULL, season_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_cultivation ON cultivation (season_id, field_id, crop_id)');
        $this->addSql('CREATE INDEX idx_acafa7974ec001d1 ON cultivation (season_id)');
        $this->addSql('CREATE INDEX idx_acafa797888579ee ON cultivation (crop_id)');
        $this->addSql('CREATE INDEX idx_acafa797443707b0 ON cultivation (field_id)');
        $this->addSql('COMMENT ON COLUMN cultivation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.field_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.crop_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cultivation.season_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE cultivation ADD CONSTRAINT fk_acafa797443707b0 FOREIGN KEY (field_id) REFERENCES field (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cultivation ADD CONSTRAINT fk_acafa797888579ee FOREIGN KEY (crop_id) REFERENCES crop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cultivation ADD CONSTRAINT fk_acafa7974ec001d1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC1204EC001D1');
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC120443707B0');
        $this->addSql('ALTER TABLE crop_rotation DROP CONSTRAINT FK_A0ECC120888579EE');
        $this->addSql('DROP INDEX IDX_A0ECC1204EC001D1');
        $this->addSql('DROP INDEX IDX_A0ECC120443707B0');
        $this->addSql('DROP INDEX IDX_A0ECC120888579EE');
        $this->addSql('ALTER TABLE crop_rotation ADD cultivation_id UUID NOT NULL');
        $this->addSql('ALTER TABLE crop_rotation DROP season_id');
        $this->addSql('ALTER TABLE crop_rotation DROP field_id');
        $this->addSql('ALTER TABLE crop_rotation DROP crop_id');
        $this->addSql('COMMENT ON COLUMN crop_rotation.cultivation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE crop_rotation ADD CONSTRAINT fk_a0ecc1203932ffbe FOREIGN KEY (cultivation_id) REFERENCES cultivation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_a0ecc1203932ffbe ON crop_rotation (cultivation_id)');
    }
}
