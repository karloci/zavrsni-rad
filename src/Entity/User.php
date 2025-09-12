<?php

namespace App\Entity;

use App\Module\User\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["user:default"])]
    private ?Uuid $id = null;

    #[ORM\Column(name: "first_name", type: Types::STRING, length: 45, unique: false, nullable: false)]
    #[Groups(["user:default"])]
    private ?string $firstName = null;

    #[ORM\Column(name: "last_name", type: Types::STRING, length: 45, unique: false, nullable: false)]
    #[Groups(["user:default"])]
    private ?string $lastName = null;

    #[ORM\Column(name: "email", type: Types::STRING, length: 180, unique: true, nullable: false)]
    #[Groups(["user:default"])]
    private ?string $email = null;

    #[ORM\Column(name: "roles", type: Types::JSON, unique: false, nullable: false)]
    #[Groups(["user:default"])]
    private array $roles = [];

    #[ORM\Column(name: "password", type: Types::STRING, length: 255, unique: false, nullable: false)]
    #[Ignore]
    private ?string $password = null;

    #[ORM\Column(name: "email_verified_at", type: Types::DATETIME_IMMUTABLE, unique: false, nullable: true)]
    #[Groups(["user:default"])]
    private ?DateTimeImmutable $emailVerifiedAt = null;

    /**
     * @var Collection<int, RefreshToken>
     */
    #[ORM\OneToMany(targetEntity: RefreshToken::class, mappedBy: "user", orphanRemoval: true)]
    private Collection $refreshTokens;

    /**
     * @var Collection<int, ResetPasswordToken>
     */
    #[ORM\OneToMany(targetEntity: ResetPasswordToken::class, mappedBy: "user", orphanRemoval: true)]
    private Collection $resetPasswordTokens;

    /**
     * @var Collection<int, VerifyEmailToken>
     */
    #[ORM\OneToMany(targetEntity: VerifyEmailToken::class, mappedBy: "user", orphanRemoval: true)]
    private Collection $verifyEmailTokens;

    #[ORM\ManyToOne(inversedBy: "users")]
    #[ORM\JoinColumn(name: "farm_id", referencedColumnName: "id", unique: false, nullable: true, onDelete: "SET NULL")]
    private ?Farm $farm = null;

    public function __construct()
    {
        $this->refreshTokens = new ArrayCollection();
        $this->resetPasswordTokens = new ArrayCollection();
        $this->verifyEmailTokens = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return $this->id->toString();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = "ROLE_USER"; // guarantee every user at least has ROLE_USER

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        if (str_starts_with($role, "ROLE_")) {
            $this->roles[] = $role;

            return $this;
        }

        throw new RuntimeException("Role '{$role} is not in correct format'");
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmailVerifiedAt(): ?DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?DateTimeImmutable $emailVerifiedAt): static
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    /**
     * @return Collection<int, RefreshToken>
     */
    public function getRefreshTokens(): Collection
    {
        return $this->refreshTokens;
    }

    public function addRefreshToken(RefreshToken $refreshToken): static
    {
        if (!$this->refreshTokens->contains($refreshToken)) {
            $this->refreshTokens->add($refreshToken);
            $refreshToken->setUser($this);
        }

        return $this;
    }

    public function removeRefreshToken(RefreshToken $refreshToken): static
    {
        if ($this->refreshTokens->removeElement($refreshToken)) {
            // set the owning side to null (unless already changed)
            if ($refreshToken->getUser() === $this) {
                $refreshToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResetPasswordToken>
     */
    public function getResetPasswordTokens(): Collection
    {
        return $this->resetPasswordTokens;
    }

    public function addResetPasswordToken(ResetPasswordToken $resetPasswordToken): static
    {
        if (!$this->resetPasswordTokens->contains($resetPasswordToken)) {
            $this->resetPasswordTokens->add($resetPasswordToken);
            $resetPasswordToken->setUser($this);
        }

        return $this;
    }

    public function removeResetPasswordToken(ResetPasswordToken $resetPasswordToken): static
    {
        if ($this->resetPasswordTokens->removeElement($resetPasswordToken)) {
            // set the owning side to null (unless already changed)
            if ($resetPasswordToken->getUser() === $this) {
                $resetPasswordToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VerifyEmailToken>
     */
    public function getVerifyEmailTokens(): Collection
    {
        return $this->verifyEmailTokens;
    }

    public function addVerifyEmailToken(VerifyEmailToken $verifyEmailToken): static
    {
        if (!$this->verifyEmailTokens->contains($verifyEmailToken)) {
            $this->verifyEmailTokens->add($verifyEmailToken);
            $verifyEmailToken->setUser($this);
        }

        return $this;
    }

    public function removeVerifyEmailToken(VerifyEmailToken $verifyEmailToken): static
    {
        if ($this->verifyEmailTokens->removeElement($verifyEmailToken)) {
            // set the owning side to null (unless already changed)
            if ($verifyEmailToken->getUser() === $this) {
                $verifyEmailToken->setUser(null);
            }
        }

        return $this;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): static
    {
        $this->farm = $farm;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
