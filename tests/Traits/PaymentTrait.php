<?php

namespace Tests\Traits;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;

trait PaymentTrait
{
    public function getCreatePaymentPayload(array $data): array
    {
        $user = User::factory()->create();

        return [
            'amount' => $data['amount'] ?? 100,
            'user_id' => $data['user_id'] ?? $user->id,
            'currency' => $data['currency'] ?? PaymentCurrencyEnum::USD->value,
            'payment_method' => $data['payment_method'] ?? PaymentMethod::factory()->make()->name,
        ];
    }
}
