<?php

namespace App\Module\VerifyEmail\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ConfirmVerifyEmailDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $token;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
