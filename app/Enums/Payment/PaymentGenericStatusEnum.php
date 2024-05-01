<?php

namespace App\Enums\Payment;

use App\Traits\EnumAttributeTrait;

enum PaymentGenericStatusEnum : string
{
    use EnumAttributeTrait;

    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case DISPUTED = 'disputed';
}
