<?php

namespace App\Http\Resources\PaymentGatewayKey;

use App\Services\PaymentGateway\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayKeyResource extends JsonResource
{

    private readonly PaymentGatewayService $paymentGatewayService;

    public function __construct(
        $resource
    )
    {
        parent::__construct($resource);
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
            'key' => $this->paymentGatewayService->getMaskedKey($this->key),
            'type' => $this->type,
            'payment_gateway' => $paymentGatewayName,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
