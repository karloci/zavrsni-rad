<?php

namespace App\Entity;

use App\Trait\SoftDelete;
use App\Module\SoilType\Repository\SoilTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SoilTypeRepository::class)]
class SoilType
{
    use SoftDelete;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["soilType:default", "field:soilType"])]
    private ?Uuid $id = null;

    #[ORM\Column(name: "name", type: Types::STRING, length: 45, unique: true, nullable: false)]
    #[Groups(["soilType:default", "field:soilType"])]
    private ?string $name = null;

    public function getId(): ?Uuid
    {
        return $this->id;
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
}
