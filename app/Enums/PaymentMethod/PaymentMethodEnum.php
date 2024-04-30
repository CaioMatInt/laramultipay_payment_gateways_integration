<?php

namespace App\Enums\PaymentMethod;

use App\Traits\EnumAttributeTrait;

enum PaymentMethodEnum : string
{
    use EnumAttributeTrait;

    case CREDIT_CARD = 'Credit Card';
    case PIX = 'PIX';
    case BOLETO = 'Boleto';
}
