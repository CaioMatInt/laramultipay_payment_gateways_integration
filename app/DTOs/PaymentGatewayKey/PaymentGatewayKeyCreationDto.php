<?php

namespace App\DTOs\PaymentGatewayKey;

use App\DTOs\BaseDto;

class PaymentGatewayKeyCreationDto extends BaseDto
{
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
