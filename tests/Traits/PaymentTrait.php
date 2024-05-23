<?php

namespace Tests\Traits;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\User;

trait PaymentTrait
{
    public function getCreatePaymentPayload(array $data): array
    {
        $user = User::factory()->create();

        if (!isset($data['payment_gateway'])) {
            $paymentGatewayName = PaymentGateway::factory()->create()->name;
        }

        return [
            'name' => $data['name'] ?? 'Payment',
            'amount' => $data['amount'] ?? 100,
            'user_id' => $data['user_id'] ?? $user->id,
            'currency' => $data['currency'] ?? PaymentCurrencyEnum::USD->value,
            'payment_method' => $data['payment_method'] ?? PaymentMethod::factory()->make()->name,
            'expires_at' => $data['expires_at'] ?? now()->addDays(1)->format('Y-m-d\TH:i'),
            'payment_gateway' => $data['payment_gateway'] ?? $paymentGatewayName,
        ];
    }
}
