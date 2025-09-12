<?php

namespace App\Module\Field\Dto;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class FieldDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $fieldType;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public Uuid $soilType;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\GreaterThan(0)]
    private float $area;

    public function getFieldType(): Uuid
    {
        return $this->fieldType;
    }

    public function setFieldType(Uuid $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    public function getSoilType(): Uuid
    {
        return $this->soilType;
    }

    public function setSoilType(Uuid $soilType): void
    {
        $this->soilType = $soilType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getArea(): float
    {
        return $this->area;
    }

    public function setArea(float $area): void
    {
        $this->area = $area;
    }
}
