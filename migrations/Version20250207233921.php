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
final class Version20250207233921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD verification_token VARCHAR(255) DEFAULT NULL');

        $this->connection->beginTransaction();
        try {
            $offset = 0;
            $batchSize = 1000;

            do {
                $users = $this->connection->fetchAllAssociative('SELECT id FROM "user" LIMIT ? OFFSET ?', [$batchSize, $offset]);
                foreach ($users as $user) {
                    $token = bin2hex(random_bytes(16));
                    $this->addSql('UPDATE "user" SET verification_token = ? WHERE id = ?', [$token, $user["id"]]);
                }
                $offset += $batchSize;
            } while (!empty($users));

            $this->connection->commit();
        }
        catch (RandomException $e) {
            $this->connection->rollBack();
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $this->addSql('ALTER TABLE "user" ALTER COLUMN verification_token SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".email_verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C1CC006B ON "user" (verification_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649C1CC006B');
        $this->addSql('ALTER TABLE "user" DROP email_verified_at');
        $this->addSql('ALTER TABLE "user" DROP verification_token');
    }
}
