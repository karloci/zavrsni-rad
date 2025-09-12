<?php

namespace App\Module\CropRotation\Dto;

use App\Validator\YearRange;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CreateCropRotationDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $season;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $field;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $crop;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type(type: DateTimeImmutable::class)]
    #[YearRange]
    private DateTimeImmutable $plantingDate;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type(type: DateTimeImmutable::class)]
    #[YearRange]
    private DateTimeImmutable $harvestDate;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->plantingDate >= $this->harvestDate) {
            $context->buildViolation("Planting date must be before harvest date")
                ->atPath("plantingDate")
                ->atPath("harvestDate")
                ->addViolation();
        }
    }

    public function getSeason(): Uuid
    {
        return $this->season;
    }

    public function setSeason(Uuid $season): void
    {
        $this->season = $season;
    }

    public function getField(): Uuid
    {
        return $this->field;
    }

    public function setField(Uuid $field): void
    {
        $this->field = $field;
    }

    public function getCrop(): Uuid
    {
        return $this->crop;
    }

    public function setCrop(Uuid $crop): void
    {
        $this->crop = $crop;
    }

    public function getPlantingDate(): DateTimeImmutable
    {
        return $this->plantingDate;
    }

    public function setPlantingDate(DateTimeImmutable $plantingDate): void
    {
        $this->plantingDate = $plantingDate;
    }

    public function getHarvestDate(): DateTimeImmutable
    {
        return $this->harvestDate;
    }

    public function setHarvestDate(DateTimeImmutable $harvestDate): void
    {
        $this->harvestDate = $harvestDate;
    }
}
