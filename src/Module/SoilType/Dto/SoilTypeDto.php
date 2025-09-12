<?php

namespace App\Module\SoilType\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SoilTypeDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
