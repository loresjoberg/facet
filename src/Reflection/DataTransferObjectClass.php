<?php

namespace LoreSjoberg\Facets\Reflection;

use LoreSjoberg\Facets\FacetInterface;
use ReflectionClass;
use ReflectionProperty;
use LoreSjoberg\Facets\Attributes\Strict;
use LoreSjoberg\Facets\DataTransferObject;
use LoreSjoberg\Facets\Exceptions\ValidationException;

class DataTransferObjectClass
{
    private ReflectionClass $reflectionClass;

    private DataTransferObject $dataTransferObject;

    private bool $isStrict;

    public function __construct(FacetInterface $dataTransferObject)
    {
        $this->reflectionClass = new ReflectionClass($dataTransferObject);
        $this->dataTransferObject = $dataTransferObject;
    }

    /**
     * @return DataTransferObjectProperty[]
     */
    public function getProperties(): array
    {
        $publicProperties = array_filter(
            $this->reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC),
            fn (ReflectionProperty $property) => ! $property->isStatic()
        );

        return array_map(
            fn (ReflectionProperty $property) => new DataTransferObjectProperty(
                $this->dataTransferObject,
                $property
            ),
            $publicProperties
        );
    }

    public function validate(): void
    {
        $validationErrors = [];

        foreach ($this->getProperties() as $property) {
            $validators = $property->getValidators();

            foreach ($validators as $validator) {
                $result = $validator->validate($property->getValue());

                if ($result->isValid) {
                    continue;
                }

                $validationErrors[$property->name][] = $result;
            }
        }

        if (count($validationErrors)) {
            throw new ValidationException($this->dataTransferObject, $validationErrors);
        }
    }

    public function isStrict(): bool
    {
        if (! isset($this->isStrict)) {
            $attribute = null;

            $reflectionClass = $this->reflectionClass;
            while ($attribute === null && $reflectionClass !== false) {
                $attribute = $reflectionClass->getAttributes(Strict::class)[0] ?? null;

                $reflectionClass = $reflectionClass->getParentClass();
            }

            $this->isStrict = $attribute !== null;
        }

        return $this->isStrict;
    }
}
