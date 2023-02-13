<?php

namespace LoreSjoberg\Facets\Tests;

use LoreSjoberg\Facets\DataTransferObject;
use LoreSjoberg\Facets\Exceptions\ValidationException;
use LoreSjoberg\Facets\Tests\Dummy\NumberBetween;

class ValidationTest extends TestCase
{
    /** @test */
    public function test_validation()
    {
        $dto = new class (foo: 50) extends DataTransferObject {
            #[NumberBetween(1, 100)]
            public int $foo;
        };

        $this->assertEquals(50, $dto->foo);

        $this->expectException(ValidationException::class);

        new class (foo: 150) extends DataTransferObject {
            #[NumberBetween(1, 100)]
            public int $foo;
        };
    }
}
