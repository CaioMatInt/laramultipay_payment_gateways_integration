<?php

namespace App\DTOs\PaymentGatewayKey;

use App\Traits\Dto\RequestDataBinderTrait;

class PaymentGatewayKeyCreationDto
{
    use RequestDataBinderTrait;

    public string $key;
    public ?string $type;
    public string $paymentGatewayId;

    /**
     * @param array{
     *     key: string,
     *     type: string,
     *     payment_gateway_id: int
     * } $data
     */
    public function __construct(array $data)
    {
        $this->key = $data['key'];
        $this->type = $data['type'] ?? null;
        $this->paymentGatewayId = $data['payment_gateway_id'];
    }
}
