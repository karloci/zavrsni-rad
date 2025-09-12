<?php

namespace App\Entity;

use App\Module\City\Repository\CityRepository;
use App\Trait\SoftDelete;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\UniqueConstraint(name: "UNIQUE_CITY", fields: ["name", "country"])]
class City
{
    use SoftDelete;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["city:default", "country:cities", "farm:default"])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: "cities")]
    #[ORM\JoinColumn(name: "country_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["city:country"])]
    private ?Country $country = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: false, nullable: false)]
    #[Groups(["city:default", "country:cities", "farm:default"])]
    private ?string $name = null;

    #[ORM\Column(name: "longitude", type: Types::FLOAT, unique: false, nullable: false)]
    #[Groups(["city:default", "country:cities", "farm:default"])]
    private ?float $longitude = null;

    #[ORM\Column(name: "latitude", type: Types::FLOAT, unique: false, nullable: false)]
    #[Groups(["city:default", "country:cities", "farm:default"])]
    private ?float $latitude = null;

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }
}
