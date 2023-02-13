<?php

namespace LoreSjoberg\Facets\Casters;

use LoreSjoberg\Facets\Caster;
use LoreSjoberg\Facets\DataTransferObject;
use LoreSjoberg\Facets\FacetInterface;

class DataTransferObjectCaster implements Caster
{
    public function __construct(
        private array $classNames
    ) {
    }

    public function cast(mixed $value): FacetInterface
    {
        foreach ($this->classNames as $className) {
            if ($value instanceof $className) {
                return $value;
            }
        }

        return new $this->classNames[0]($value);
    }
}
