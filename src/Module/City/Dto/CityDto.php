<?php

namespace App\Module\City\Dto;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class CityDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("numeric")]
    #[Assert\Range(min: -180, max: 180)]
    private float $longitude;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("numeric")]
    #[Assert\Range(min: -90, max: 90)]
    private float $latitude;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }
}
