<?php

namespace App\Module\CropRotation\Dto;

use App\Validator\YearRange;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UpdateCropRotationDto
{
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
