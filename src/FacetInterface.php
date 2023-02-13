<?php

namespace LoreSjoberg\Facets;

interface FacetInterface
{
    public function toJson(): string;

    public function all(): array;

    public function only(string ...$keys): FacetInterface;

    public function except(string ...$keys): FacetInterface;

    public function clone(...$args): FacetInterface;

    public function toArray(): array;
}
