<?php

namespace LoreSjoberg\Facets\Exceptions;

use Exception;
use LoreSjoberg\Facets\DataTransferObject;
use LoreSjoberg\Facets\Validation\ValidationResult;

class ValidationException extends Exception
{
    public function __construct(
        public DataTransferObject $dataTransferObject,
        public array $validationErrors,
    ) {
        $className = $dataTransferObject::class;

        $messages = [];

        foreach ($validationErrors as $fieldName => $errorsForField) {
            /** @var ValidationResult $errorForField */
            foreach ($errorsForField as $errorForField) {
                $messages[] = "\t - `$className->$fieldName`: $errorForField->message";
            }
        }

        parent::__construct("Validation errors:" . PHP_EOL . implode(PHP_EOL, $messages));
    }
}
