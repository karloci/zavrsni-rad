<?php

namespace App\Trait;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

trait DeletedLog
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "deleted_by", referencedColumnName: "id", nullable: true)]
    #[Groups(["log:deleted"])]
    private ?UserInterface $deletedBy = null;

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

    public function getDeletedBy(): ?UserInterface
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(UserInterface $deletedBy): self
    {
        $this->deletedBy = $deletedBy;
        return $this;
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

    public function markAsDeleted(UserInterface $user): void
    {
        $this->deletedAt = new DateTimeImmutable();
        $this->deletedBy = $user;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
