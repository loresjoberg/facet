<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\Attributes\Strict;
use LoreSjoberg\Facets\DataTransferObject;

#[Strict]
class ComplexStrictDto extends DataTransferObject
{
    public string $name;

    public BasicDto $other;
}
