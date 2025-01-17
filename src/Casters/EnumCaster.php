<?php

namespace LoreSjoberg\Facets\Casters;

use BackedEnum;
use LogicException;
use LoreSjoberg\Facets\Caster;

class EnumCaster implements Caster
{
    public function __construct(
        private array $types,
        private string $enumType
    ) {
    }

    public function cast(mixed $value): BackedEnum
    {
        if ($value instanceof $this->enumType) {
            return $value;
        }

        if (! is_subclass_of($this->enumType, 'BackedEnum')) {
            throw new LogicException("Caster [EnumCaster] may only be used to cast backed enums. Received [$this->enumType].");
        }

        $castedValue = $this->enumType::tryFrom($value);

        if ($castedValue === null) {
            throw new LogicException("Couldn't cast enum [$this->enumType] with value [$value]");
        }

        return $castedValue;
    }
}
