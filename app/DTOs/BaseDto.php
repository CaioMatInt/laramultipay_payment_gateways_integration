<?php

namespace App\DTOs;

abstract class BaseDto
{
    /**
     * Creates an instance of the called class from the provided request data.
     *
     * This method uses reflection to dynamically instantiate the subclass (Dto)
     * that called this method, passing the given data to the constructor.
     *
     * @param array $data
     * @throws \ReflectionException
     */
    public static function fromRequest(array $data): self
    {
        $calledClass = get_called_class();
        $reflectionClass = new \ReflectionClass($calledClass);
        return $reflectionClass->newInstance($data);
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
