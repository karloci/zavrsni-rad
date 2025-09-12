<?php

namespace App\Trait;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

trait UpdatedLog
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "updated_by", referencedColumnName: "id", nullable: true)]
    #[Groups(["log:updated"])]
    private ?UserInterface $updatedBy = null;

    #[ORM\Column(name: "updated_at", type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(["log:updated"])]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\PreUpdate]
    public function updated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(UserInterface $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
