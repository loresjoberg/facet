<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use Attribute;
use LoreSjoberg\Facets\Validation\ValidationResult;
use LoreSjoberg\Facets\Validator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class NumberBetween implements Validator
{
    public function __construct(
        private int $min,
        private int $max
    ) {
    }

    public function validate(mixed $value): ValidationResult
    {
        if ($value < $this->min) {
            return new ValidationResult(false, "Value should be greater than or equal to {$this->min}");
        }

        if ($value > $this->max) {
            return new ValidationResult(false, "Value should be less than or equal to {$this->max}");
        }

        return new ValidationResult(true);
    }
}
