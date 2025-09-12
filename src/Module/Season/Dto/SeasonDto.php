<?php

namespace App\Module\Season\Dto;

use App\Validator\YearRange;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SeasonDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 45)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type(type: DateTimeImmutable::class)]
    #[YearRange]
    private DateTimeImmutable $startDate;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type(type: DateTimeImmutable::class)]
    #[YearRange]
    private DateTimeImmutable $endDate;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->startDate >= $this->endDate) {
            $context->buildViolation("Start date must be before end date")
                ->atPath("startDate")
                ->atPath("endDate")
                ->addViolation();
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
    }
}
