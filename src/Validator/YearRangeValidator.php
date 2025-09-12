<?php

namespace App\Validator;

use DateTimeInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class YearRangeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var YearRange $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof DateTimeInterface) {
            return;
        }

        $year = (int)$value->format("Y");
        $currentYear = (int)date("Y");
        $minYear = $constraint->minYear ?? $currentYear - 1;
        $maxYear = $constraint->maxYear ?? $currentYear + 4;

        if ($year < $minYear || $year > $maxYear) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $year)
                ->setParameter('{{ minYear }}', $minYear)
                ->setParameter('{{ maxYear }}', $maxYear)
                ->addViolation();
        }
    }
}
