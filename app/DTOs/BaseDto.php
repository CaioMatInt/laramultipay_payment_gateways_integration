<?php

namespace App\DTOs;

abstract class BaseDto
{

    /**
     * BaseDto constructor.
     *
     * @param array $data
     * @return static
     */
    public function __construct(array $data)
    {
        $reflectionClass = new \ReflectionClass($this);
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $data)) {
                $this->{$propertyName} = $data[$propertyName];
            }
        }
    }

    /**
     * Convert object properties to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
