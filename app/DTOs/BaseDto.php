<?php

namespace App\DTOs;

abstract class BaseDto
{
    /**
     * BaseDto constructor.
     *
     * @param array $data
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
