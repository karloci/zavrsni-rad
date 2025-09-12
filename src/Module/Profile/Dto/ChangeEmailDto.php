<?php

namespace App\Module\Profile\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ChangeEmailDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    private string $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}