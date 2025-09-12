<?php

namespace App\Module\ResetPassword\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ConfirmResetPasswordDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $token;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 8, max: 64)]
    private string $password;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 64)]
    private string $repeatPassword;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->getPassword() !== $this->getRepeatPassword()) {
            $context->buildViolation("Repeat password do not match!")
                ->atPath("repeatPassword")
                ->addViolation();
        }
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getRepeatPassword(): ?string
    {
        return $this->repeatPassword;
    }

    public function setRepeatPassword(?string $repeatPassword): void
    {
        $this->repeatPassword = $repeatPassword;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
