<?php

namespace App\Http\Resources\Payment;

use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Payment */
class PaymentResource extends JsonResource
{

    private readonly PaymentGenericStatusService $paymentGenericStatusService;
    private readonly PaymentMethodService $paymentMethodService;

    public function __construct(
        $resource
    )
    {
        parent::__construct($resource);
        $this->paymentGenericStatusService = app(PaymentGenericStatusService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        $paymentGenericStatusName = $this
            ->paymentGenericStatusService
            ->findCached($this->payment_generic_status_id)
            ->name;

        $paymentMethodName = $this
            ->paymentMethodService
            ->findCached($this->payment_method_id)
            ->name;

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payment_generic_status' => $paymentGenericStatusName,
            'payment_method' => $paymentMethodName,
        ];
    }
}
