<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\Attributes\DefaultCast;
use LoreSjoberg\Facets\DataTransferObject;

#[DefaultCast(ComplexObject::class, ComplexObjectCaster::class)]
class ComplexDtoWithCast extends DataTransferObject
{
    public string $name;

    public ComplexObject $object;
}
