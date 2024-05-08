<?php

namespace App\Http\Resources\Payment;

use App\Services\PaymentGateway\PaymentGatewayService;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Payment */
class PaymentResource extends JsonResource
{

    private readonly PaymentGenericStatusService $paymentGenericStatusService;
    private readonly PaymentMethodService $paymentMethodService;
    private readonly PaymentGatewayService $paymentGatewayService;

    public function __construct(
        $resource
    )
    {
        parent::__construct($resource);
        $this->paymentGenericStatusService = app(PaymentGenericStatusService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->paymentGatewayService = app(PaymentGatewayService::class);
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

        $paymentGatewayName = $this
            ->paymentGatewayService
            ->findCached($this->payment_gateway_id)
            ->name;

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_generic_status' => $paymentGenericStatusName,
            'payment_method' => $paymentMethodName,
            'expires_at' => $this->expires_at->format('Y-m-d H:i'),
            'payment_gateway' => $paymentGatewayName,
        ];
    }
}
