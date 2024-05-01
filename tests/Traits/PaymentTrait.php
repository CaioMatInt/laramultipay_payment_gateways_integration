<?php

namespace Tests\Traits;

use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;

trait PaymentTrait
{
    public function getCreatePaymentPayload(array $data): array
    {
        $paymentFactoryGenericData = Payment::factory()->make();

        return [
            'amount' => $data['amount'] ?? $paymentFactoryGenericData->amount,
            'user_id' => $data['user_id'] ?? $paymentFactoryGenericData->user_id,
            'currency' => $data['currency'] ?? $paymentFactoryGenericData->currency,
            'payment_method' => $data['payment_method'] ?? PaymentMethod::factory()->make()->name,
        ];
    }
}
