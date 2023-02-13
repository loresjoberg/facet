<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\DataTransferObject;

class ComplexDtoWithNullableProperty extends DataTransferObject
{
    public string $name;

    public ?BasicDto $other;
}
