<?php

namespace App\Traits\Dto;

use App\DTOs\Authentication\LoginDto;
use App\DTOs\Payment\PaymentCreationDto;
use App\DTOs\PaymentGatewayKey\PaymentGatewayKeyCreationDto;
use App\DTOs\User\UserCreationDto;

trait RequestDataBinderTrait
{
    /**
     * @param array $data
     * @return LoginDto|PaymentCreationDto|PaymentGatewayKeyCreationDto|UserCreationDto|RequestDataBinderTrait
     */
    public static function fromRequest(array $data): self
    {
        return new self($data);
    }
}
