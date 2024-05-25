<?php

namespace App\DTOs\Payment;

use App\DTOs\BaseDto;
use Carbon\Carbon;

class PaymentCreationDto extends BaseDto
{
    public string $name;
    public int $amount;
    public string $currency;
    public string $paymentMethod;
    public ?Carbon $expiresAt;
    public ?string $paymentGateway;
}
