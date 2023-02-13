<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\DataTransferObject;

class ComplexDtoWithCastedAttributeHavingCast extends DataTransferObject
{
    public string $name;

    public ComplexDtoWithCast $other;
}
