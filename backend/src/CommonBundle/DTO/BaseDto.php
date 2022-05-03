<?php

namespace App\CommonBundle\DTO;

use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

class BaseDto implements BaseDtoInterface
{
    protected ReflectionClass $reflectionClass;

    private array $properties;

    public function __construct(array $parameters = [])
    {
        $this->reflectionClass = new ReflectionClass($this);
        $this->properties = $this->getProperties();

        if (count($parameters) > 0) {
            foreach ($parameters as $key => $parameter) {
                if (in_array($key, $this->properties)) {
                    $this->$key = $parameter;
                }
            }
        }
    }

    public function toArray(): array
    {
        $result = [];

        $properties = $this->getProperties();

        foreach ($properties as $property) {
            $result[$property] = $this->$property;
        }

        return $result;
    }

    public function getProperties(): array
    {
        $properties =  array_filter(
            $this->reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC),
            fn (ReflectionProperty $property) => ! $property->isStatic()
        );

        $propertyNames = [];

        foreach ($properties as $property) {
            $propertyNames[] = $property->name;
        }

        return $propertyNames;
    }
}
