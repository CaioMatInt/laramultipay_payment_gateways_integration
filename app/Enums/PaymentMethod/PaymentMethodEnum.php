<?php

namespace App\Enums\PaymentMethod;

use App\Traits\Enum\EnumAttributeTrait;

enum PaymentMethodEnum : string
{
    use EnumAttributeTrait;

    case CREDIT_CARD = 'credit_card';
    case PIX = 'pix';
    case BOLETO = 'boleto';
}
