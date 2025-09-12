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
final class Version20250208134904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649BEAB6C24 ON "user" (password_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649BEAB6C24');
        $this->addSql('ALTER TABLE "user" DROP password_token');
    }
}
