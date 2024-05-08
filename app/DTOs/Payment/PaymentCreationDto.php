<?php

namespace App\DTOs\Payment;

use App\Traits\Dto\RequestDataBinderTrait;
use Carbon\Carbon;

class PaymentCreationDto
{
    use RequestDataBinderTrait;

    public string $name;
    public int $amount;
    public string $currency;
    public string $paymentMethod;
    public ?Carbon $expiresAt;
    public ?string $paymentGateway;

    /**
     * @param array{
     *     name: string,
     *     amount: integer,
     *     currency: string,
     *     payment_method: string,
     *     expires_at: ?Carbon,
     *     payment_gateway: string
     * } $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->paymentMethod = $data['payment_method'];
        $this->expiresAt = $data['expires_at'] ?? null;
        $this->paymentGateway = $data['payment_gateway'];
    }
}
