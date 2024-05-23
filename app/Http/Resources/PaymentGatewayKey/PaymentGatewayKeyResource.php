<?php

namespace App\Http\Resources\PaymentGatewayKey;

use App\Services\PaymentGateway\PaymentGatewayService;
use App\Services\PaymentGatewayKey\PaymentGatewayKeyService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayKeyResource extends JsonResource
{

    private readonly PaymentGatewayKeyService $paymentGatewayKeyService;
    private readonly PaymentGatewayService $paymentGatewayService;

    public function __construct(
        $resource
    )
    {
        parent::__construct($resource);
        $this->paymentGatewayKeyService = app(PaymentGatewayKeyService::class);
        $this->paymentGatewayService = app(PaymentGatewayService::class);
    }

    public function toArray(Request $request): array
    {
        $paymentGatewayName = $this
            ->paymentGatewayService
            ->findCached($this->payment_gateway_id)
            ->name;

        return [
            'id' => $this->id,
            'key' => $this->paymentGatewayKeyService->getMaskedKey($this->key),
            'type' => $this->type,
            'payment_gateway' => $paymentGatewayName,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
