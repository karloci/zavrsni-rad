<?php

namespace App\Trait;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Serializer\Attribute\Groups;

trait SoftDelete
{
    #[ORM\Column(name: "deleted_at", type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(["log:deleted"])]
    private ?DateTimeImmutable $deletedAt = null;

    /**
     * @throws RuntimeException
     */
    #[ORM\PreRemove]
    public function preventRemove(): void
    {
        //throw new RuntimeException("This object cannot be removed from the database due to the soft delete option. Please use the 'markAsDeleted' method!");
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function markAsDeleted(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
