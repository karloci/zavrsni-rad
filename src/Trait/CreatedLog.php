<?php

namespace App\Trait;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

trait CreatedLog
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "created_by", referencedColumnName: "id", nullable: false)]
    #[Groups(["log:created"])]
    private ?UserInterface $createdBy = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_IMMUTABLE)]
    #[Groups(["log:created"])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function created(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(UserInterface $createdBy): self
    {
        if (!is_null($this->createdBy)) {
            throw new RuntimeException("Entity \"{$this->getParentClassName()}\" already has value for \"createdBy\" property");
        }

        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    private function getParentClassName(): string
    {
        return basename(str_replace('\\', '/', static::class));
    }
}
