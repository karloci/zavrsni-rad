<?php

namespace App\Module\ResetPassword\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RequestResetPasswordDto
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
