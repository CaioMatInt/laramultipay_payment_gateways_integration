<?php

namespace App\Enums\Payment;

use App\Traits\Enum\EnumAttributeTrait;

enum PaymentCurrencyEnum : string
{
    use EnumAttributeTrait;

    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case BRL = 'BRL';
}
