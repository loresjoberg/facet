<?php

namespace LoreSjoberg\Facets;

use LoreSjoberg\Facets\Exceptions\ValidationException;
use ReflectionClass;
use ReflectionProperty;
use LoreSjoberg\Facets\Attributes\CastWith;
use LoreSjoberg\Facets\Attributes\MapTo;
use LoreSjoberg\Facets\Casters\DataTransferObjectCaster;
use LoreSjoberg\Facets\Exceptions\UnknownProperties;
use LoreSjoberg\Facets\Reflection\DataTransferObjectClass;

#[CastWith(DataTransferObjectCaster::class)]
abstract class DataTransferObject implements FacetInterface
{
    protected array $exceptKeys = [];

    protected array $onlyKeys = [];

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __construct(...$args) {

        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        $args['root'] = &$args;

        $args = $this->preProcess($args);

        $trackedArgs = $args;
        $class = new DataTransferObjectClass($this);

        foreach ($class->getProperties() as $property) {
            $property->setValue(Arr::get($args, $property->name, $property->getDefaultValue()));
            $trackedArgs = Arr::forget($trackedArgs, $property->name);
        }

        $this->checkForStrict($class, $trackedArgs);

        $class->validate();
    }

    public function toJson(): string
    {
        return $this->jsonEncode($this->toArray());
    }

    protected  function jsonEncode(mixed $value, $addedFlags = ''): bool|string
    {
        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR;

        if ($addedFlags) {
            $flags = $flags | $addedFlags;
        }

        return json_encode($value, $flags);
    }

    protected function preProcess(array $input): array
    {
        return $input;
    }

    /**
     * @throws UnknownProperties
     * @codeCoverageIgnore
     */
    protected function checkForStrict(DataTransferObjectClass $class, array $trackedArgs): void
    {
        if ($class->isStrict() && count($trackedArgs)) {
            throw UnknownProperties::new(static::class, array_keys($trackedArgs));
        }
    }


    public static function arrayOf(array $arrayOfParameters): array
    {
        return array_map(
            fn (mixed $parameters) => new static($parameters),
            $arrayOfParameters
        );
    }

    public function all(): array
    {
        $data = [];

        $class = new ReflectionClass(static::class);

        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $mapToAttribute = $property->getAttributes(MapTo::class);
            $name = count($mapToAttribute) ? $mapToAttribute[0]->newInstance()->name : $property->getName();

            $data[$name] = $property->getValue($this);
        }

        return $data;
    }

    public function only(string ...$keys): FacetInterface
    {
        $dataTransferObject = clone $this;

        $dataTransferObject->onlyKeys = [...$this->onlyKeys, ...$keys];

        return $dataTransferObject;
    }

    public function except(string ...$keys): FacetInterface
    {
        $dataTransferObject = clone $this;

        $dataTransferObject->exceptKeys = [...$this->exceptKeys, ...$keys];

        return $dataTransferObject;
    }

    public function clone(...$args): FacetInterface
    {
        return new static(...array_merge($this->toArray(), $args));
    }

    public function toArray(): array
    {
        if (count($this->onlyKeys)) {
            $array = Arr::only($this->all(), $this->onlyKeys);
        } else {
            $array = Arr::except($this->all(), $this->exceptKeys);
        }

        return $this->parseArray($array);
    }

    protected function parseArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if ($value instanceof DataTransferObject) {
                $array[$key] = $value->toArray();

                continue;
            }

            if (! is_array($value)) {
                continue;
            }

            $array[$key] = $this->parseArray($value);
        }

        return $array;
    }
}
