<?php

namespace App\Module\Timezone\Dto;

use DateTimeZone;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TimezoneDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $code;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (!in_array($this->getCode(), DateTimeZone::listIdentifiers(), true)) {
            $context->buildViolation("The provided timezone code \"{{ code }}\" is not valid.")
                ->setParameter("{{ code }}", $this->getCode())
                ->atPath("code")
                ->addViolation();
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
