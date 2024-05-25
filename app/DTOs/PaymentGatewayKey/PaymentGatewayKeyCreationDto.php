<?php

namespace App\DTOs\PaymentGatewayKey;

use App\DTOs\BaseDto;

class PaymentGatewayKeyCreationDto extends BaseDto
{
    public string $key;
    public ?string $type;
    public string $paymentGatewayId;
}
