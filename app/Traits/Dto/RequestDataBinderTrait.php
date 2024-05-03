<?php

namespace App\Traits\Dto;

trait RequestDataBinderTrait
{
    /**
     * @param array{ email: string, password: string } $data
     */
    public static function fromRequest(array $data): self
    {
        return new self($data);
    }
}
