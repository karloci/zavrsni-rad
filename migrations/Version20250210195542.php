<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210195542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE farm (id UUID NOT NULL, city_id UUID NOT NULL, timezone_id UUID NOT NULL, created_by UUID NOT NULL, updated_by UUID DEFAULT NULL, deleted_by UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5816D0458BAC62AF ON farm (city_id)');
        $this->addSql('CREATE INDEX IDX_5816D0453FE997DE ON farm (timezone_id)');
        $this->addSql('CREATE INDEX IDX_5816D045DE12AB56 ON farm (created_by)');
        $this->addSql('CREATE INDEX IDX_5816D04516FE72E1 ON farm (updated_by)');
        $this->addSql('CREATE INDEX IDX_5816D0451F6FA0AF ON farm (deleted_by)');
        $this->addSql('COMMENT ON COLUMN farm.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN farm.city_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN farm.timezone_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN farm.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN farm.updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN farm.deleted_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN farm.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE farm ADD CONSTRAINT FK_5816D0458BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE farm ADD CONSTRAINT FK_5816D0453FE997DE FOREIGN KEY (timezone_id) REFERENCES timezone (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE farm ADD CONSTRAINT FK_5816D045DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE farm ADD CONSTRAINT FK_5816D04516FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE farm ADD CONSTRAINT FK_5816D0451F6FA0AF FOREIGN KEY (deleted_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD farm_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "user".farm_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64965FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64965FCFA0D ON "user" (farm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64965FCFA0D');
        $this->addSql('ALTER TABLE farm DROP CONSTRAINT FK_5816D0458BAC62AF');
        $this->addSql('ALTER TABLE farm DROP CONSTRAINT FK_5816D0453FE997DE');
        $this->addSql('ALTER TABLE farm DROP CONSTRAINT FK_5816D045DE12AB56');
        $this->addSql('ALTER TABLE farm DROP CONSTRAINT FK_5816D04516FE72E1');
        $this->addSql('ALTER TABLE farm DROP CONSTRAINT FK_5816D0451F6FA0AF');
        $this->addSql('DROP TABLE farm');
        $this->addSql('DROP INDEX IDX_8D93D64965FCFA0D');
        $this->addSql('ALTER TABLE "user" DROP farm_id');
    }
}
