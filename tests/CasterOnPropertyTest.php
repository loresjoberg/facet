<?php

namespace LoreSjoberg\Facets\Tests;

use LoreSjoberg\Facets\Attributes\CastWith;
use LoreSjoberg\Facets\DataTransferObject;
use LoreSjoberg\Facets\Tests\Dummy\ComplexObject;
use LoreSjoberg\Facets\Tests\Dummy\ComplexObjectCaster;

class CasterOnPropertyTest extends TestCase
{
    /** @test */
    public function property_is_casted()
    {
        $dto = new class (complexObject: [ 'name' => 'test' ]) extends DataTransferObject {
            #[CastWith(ComplexObjectCaster::class)]
            public ComplexObject $complexObject;
        };

        $this->assertEquals('test', $dto->complexObject->name);
    }
}
