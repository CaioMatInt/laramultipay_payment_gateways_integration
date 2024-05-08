<?php

namespace App\Enums\PaymentGateway;

use App\Traits\Enum\EnumAttributeTrait;

enum PaymentGatewayEnum : string
{
    use EnumAttributeTrait;

    case STRIPE = 'stripe';
    case GERENCIA_NET = 'gerencia_net';
}
