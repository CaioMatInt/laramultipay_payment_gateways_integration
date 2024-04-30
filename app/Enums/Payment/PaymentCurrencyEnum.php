<?php

namespace App\Enums\Payment;

use App\Traits\EnumAttributeHandlerTrait;

enum PaymentCurrencyEnum : string
{
    use EnumAttributeHandlerTrait;

    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case BRL = 'BRL';
}
