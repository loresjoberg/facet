<?php

namespace LoreSjoberg\Facets;

interface Caster
{
    public function cast(mixed $value): mixed;
}
