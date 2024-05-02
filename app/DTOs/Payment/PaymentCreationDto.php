<?php

namespace App\DTOs\Payment;

use App\Traits\DtoHandleDataTrait;

class PaymentCreationDto
{
    use DtoHandleDataTrait;

    public int $amount;
    public string $currency;
    public string $payment_method;

    /**
     * @param array{ amount: integer, currency: string, payment_method: string } $data
     */

    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->payment_method = $data['payment_method'];
    }
}
