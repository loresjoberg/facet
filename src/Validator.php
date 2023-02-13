<?php

namespace LoreSjoberg\Facets;

use LoreSjoberg\Facets\Validation\ValidationResult;

interface Validator
{
    public function validate(mixed $value): ValidationResult;
}
