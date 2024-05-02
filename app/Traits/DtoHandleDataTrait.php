<?php

namespace App\Traits;

trait DtoHandleDataTrait
{
    /**
     * @param array{ email: string, password: string } $data
     */
    public static function fromRequest(array $data): self
    {
        return new self($data);
    }
}
