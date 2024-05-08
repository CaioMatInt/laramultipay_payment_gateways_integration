<?php

namespace App\Enums\PaymentGatewayKey;

use App\Traits\EnumAttributeTrait;

enum PaymentGatewayKeyTypes : string
{
    use EnumAttributeTrait;

    case SECRET_KEY = 'secret_key';
}
