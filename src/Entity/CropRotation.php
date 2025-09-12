<?php

namespace App\Entity;

use App\Trait\CreatedLog;
use App\Trait\UpdatedLog;
use App\Module\CropRotation\Repository\CropRotationRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CropRotationRepository::class)]
#[ORM\UniqueConstraint(name: "UNIQUE_CROP_ROTATION", fields: ["season", "field", "crop"])]
#[ORM\HasLifecycleCallbacks]
class CropRotation
{
    use CreatedLog, UpdatedLog;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[Groups(["cropRotation:default"])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: "cropRotations")]
    #[ORM\JoinColumn(name: "season_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["cropRotation:season", "season:default"])]
    private ?Season $season = null;

    #[ORM\ManyToOne(inversedBy: "cropRotations")]
    #[ORM\JoinColumn(name: "field_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["cropRotation:field", "field:default"])]
    private ?Field $field = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "crop_id", referencedColumnName: "id", unique: false, nullable: false)]
    #[Groups(["cropRotation:crop", "crop:default"])]
    private ?Crop $crop = null;

    #[ORM\Column(name: "planting_date", type: Types::DATE_IMMUTABLE, unique: false, nullable: true)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => "Y-m-d"])]
    #[Groups(["cropRotation:default"])]
    private ?DateTimeImmutable $plantingDate = null;

    #[ORM\Column(name: "harvest_date", type: Types::DATE_IMMUTABLE, unique: false, nullable: true)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => "Y-m-d"])]
    #[Groups(["cropRotation:default"])]
    private ?DateTimeImmutable $harvestDate = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getCrop(): ?Crop
    {
        return $this->crop;
    }

    public function setCrop(?Crop $crop): static
    {
        $this->crop = $crop;

        return $this;
    }

    public function getPlantingDate(): ?DateTimeImmutable
    {
        return $this->plantingDate;
    }

    public function setPlantingDate(?DateTimeImmutable $plantingDate): void
    {
        $this->plantingDate = $plantingDate;
    }

    public function getHarvestDate(): ?DateTimeImmutable
    {
        return $this->harvestDate;
    }

    public function setHarvestDate(?DateTimeImmutable $harvestDate): void
    {
        $this->harvestDate = $harvestDate;
    }
}
