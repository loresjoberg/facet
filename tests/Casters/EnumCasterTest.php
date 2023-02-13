<?php

namespace LoreSjoberg\Facets\Tests\Casters;

use LogicException;
use LoreSjoberg\Facets\Attributes\CastWith;
use LoreSjoberg\Facets\Casters\EnumCaster;
use LoreSjoberg\Facets\DataTransferObject;
use LoreSjoberg\Facets\Tests\Stubs\IntegerEnum;
use LoreSjoberg\Facets\Tests\Stubs\SimpleEnum;
use LoreSjoberg\Facets\Tests\Stubs\StringEnum;
use LoreSjoberg\Facets\Tests\TestCase;

/** @requires PHP >= 8.1 */
class EnumCasterTest extends TestCase
{
    /** @test */
    public function test_it_cannot_cast_enum_with_wrong_value_type_given(): void
    {
        $wrongValue = 5;

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'Couldn\'t cast enum [' . StringEnum::class . '] with value [' . $wrongValue . ']'
        );

        new EnumCastedDataTransferObject([
            'stringEnum' => $wrongValue,
        ]);
    }

    /** @test */
    public function test_it_can_cast_enums(): void
    {
        $dto = new EnumCastedDataTransferObject([
            'integerEnum' => 1,
            'stringEnum' => 'test',
        ]);

        $this->assertEquals(StringEnum::Test, $dto->stringEnum);
        $this->assertEquals(IntegerEnum::Test, $dto->integerEnum);
    }

    /** @test */
    public function test_it_can_cast_enums_which_are_already_enums(): void
    {
        $dto = new EnumCastedDataTransferObject([
            'integerEnum' => IntegerEnum::Test,
            'stringEnum' => StringEnum::Test,
        ]);

        $this->assertEquals(StringEnum::Test, $dto->stringEnum);
        $this->assertEquals(IntegerEnum::Test, $dto->integerEnum);
    }

    /** @test */
    public function test_it_cannot_cast_simple_enums(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'Caster [EnumCaster] may only be used to cast backed enums. Received [' . SimpleEnum::class . '].'
        );

        new EnumCastedDataTransferObject([
            'simpleEnum' => 5,
        ]);
    }
}

class EnumCastedDataTransferObject extends DataTransferObject
{
    #[CastWith(EnumCaster::class, StringEnum::class)]
    public ?StringEnum $stringEnum;

    #[CastWith(EnumCaster::class, IntegerEnum::class)]
    public ?IntegerEnum $integerEnum;

    #[CastWith(EnumCaster::class, SimpleEnum::class)]
    public ?SimpleEnum $simpleEnum;
}
