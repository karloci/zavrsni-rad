<?php

namespace App\Entity;

use App\Module\ResetPassword\Repository\ResetPasswordTokenRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResetPasswordTokenRepository::class)]
class ResetPasswordToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: Types::INTEGER, unique: true, nullable: false)]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: "resetPasswordTokens")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", unique: false, nullable: false)]
    private ?User $user = null;

    #[ORM\Column(name: "token", type: Types::STRING, length: 45, unique: true, nullable: false)]
    private ?string $token = null;

    #[ORM\Column(name: "expires_at", type: Types::DATETIME_IMMUTABLE, unique: false, nullable: false)]
    private ?DateTimeImmutable $expiresAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
