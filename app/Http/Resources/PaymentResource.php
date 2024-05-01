<?php

namespace App\Http\Resources;

use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Payment */
class PaymentResource extends JsonResource
{

    public function __construct(
        $resource,
        private readonly PaymentGenericStatusService $paymentGenericStatusService,
        private readonly PaymentMethodService $paymentMethodService,
    )
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {

        $paymentGenericStatusName = $this
            ->paymentGenericStatusService
            ->find($this->payment_generic_status_id)
            ->name;

        $paymentMethodName = $this
            ->paymentMethodService
            ->find($this->payment_method_id)
            ->name;

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payment_generic_status' => $paymentGenericStatusName,
            'payment_method' => $paymentMethodName,
        ];
    }
}
