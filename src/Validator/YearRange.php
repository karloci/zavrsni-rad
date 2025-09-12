<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class YearRange extends Constraint
{
    public string $message = 'The year "{{ value }}" must be between {{ minYear }} and {{ maxYear }}.';

    public function __construct(
        public string $mode = "strict",
        ?array        $groups = null,
        mixed         $payload = null
    )
    {
        parent::__construct([], $groups, $payload);
    }
}
