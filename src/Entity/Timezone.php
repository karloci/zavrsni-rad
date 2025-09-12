<?php

namespace App\Entity;

use App\Trait\SoftDelete;
use App\Module\Timezone\Repository\TimezoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TimezoneRepository::class)]
class Timezone
{
    use SoftDelete;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["timezone:default", "country:timezones", "farm:default"])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: "timezones")]
    #[ORM\JoinColumn(name: "country_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["timezone:country"])]
    private ?Country $country = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: true, nullable: false)]
    #[Groups(["timezone:default", "country:timezones", "farm:default"])]
    private ?string $name = null;

    #[ORM\Column(name: "code", type: Types::STRING, length: 45, unique: true, nullable: false)]
    #[Groups(["timezone:default", "country:timezones", "farm:default"])]
    private ?string $code = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
