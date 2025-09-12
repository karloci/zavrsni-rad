<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Random\RandomException;
use RuntimeException;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209152018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_token (id SERIAL NOT NULL, user_id UUID NOT NULL, token TEXT NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_452C9EC55F37A13B ON reset_password_token (token)');
        $this->addSql('CREATE INDEX IDX_452C9EC5A76ED395 ON reset_password_token (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_token.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_token.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reset_password_token ADD CONSTRAINT FK_452C9EC5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_8d93d649beab6c24');
        $this->addSql('ALTER TABLE "user" DROP password_token');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_token DROP CONSTRAINT FK_452C9EC5A76ED395');
        $this->addSql('DROP TABLE reset_password_token');
        $this->addSql('ALTER TABLE "user" ADD password_token VARCHAR(255) DEFAULT NULL');

        $this->connection->beginTransaction();
        try {
            $offset = 0;
            $batchSize = 1000;

            do {
                $users = $this->connection->fetchAllAssociative('SELECT id FROM "user" LIMIT ? OFFSET ?', [$batchSize, $offset]);
                foreach ($users as $user) {
                    $token = bin2hex(random_bytes(16));
                    $this->addSql('UPDATE "user" SET password_token = ? WHERE id = ?', [$token, $user["id"]]);
                }
                $offset += $batchSize;
            } while (!empty($users));

            $this->connection->commit();
        }
        catch (RandomException $e) {
            $this->connection->rollBack();
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $this->addSql('ALTER TABLE "user" ALTER COLUMN password_token SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649beab6c24 ON "user" (password_token)');
    }
}
