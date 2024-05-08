<?php

namespace App\Enums\PaymentGatewayKey;

use App\Traits\Enum\EnumAttributeTrait;

enum PaymentGatewayKeyTypes : string
{
    use EnumAttributeTrait;

    case SECRET_KEY = 'secret_key';
}
