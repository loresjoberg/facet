<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\DataTransferObject;

class ComplexDto extends DataTransferObject
{
    public string $name;

    public BasicDto $other;
}
