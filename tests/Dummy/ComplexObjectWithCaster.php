<?php

namespace LoreSjoberg\Facets\Tests\Dummy;

use LoreSjoberg\Facets\Attributes\CastWith;

#[CastWith(ComplexObjectWithCasterCaster::class)]
class ComplexObjectWithCaster
{
    public function __construct(
        public string $name,
    ) {
    }
}
