<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\DataTransferObject;

class ComplexDtoWithSelf extends DataTransferObject
{
    public string $name;

    public ?self $other;
}
