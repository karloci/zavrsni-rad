<?php

namespace App\Module\Country\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CountryDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(exactly: 3)]
    private string $code;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
