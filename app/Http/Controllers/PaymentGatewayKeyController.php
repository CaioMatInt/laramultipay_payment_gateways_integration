<?php

namespace App\Http\Controllers;

use App\DTOs\PaymentGatewayKey\PaymentGatewayKeyCreationDto;
use App\Http\Requests\PaymentGatewayKey\StorePaymentGatewayKeyRequest;
use App\Http\Resources\PaymentGatewayKey\PaymentGatewayKeyResource;
use App\Services\PaymentGatewayKey\PaymentGatewayKeyService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentGatewayKeyController extends Controller
{

    public function __construct(private readonly PaymentGatewayKeyService $paymentGatewayKeyService)
    {
    }

    public function index(): ResourceCollection
    {
        $paymentGatewayKeys = $this->paymentGatewayKeyService->getAll();

        return PaymentGatewayKeyResource::collection($paymentGatewayKeys);
    }

    public function store(StorePaymentGatewayKeyRequest $request): PaymentGatewayKeyResource
    {
        $dto = PaymentGatewayKeyCreationDto::fromRequest($request->only(['key', 'type', 'payment_gateway_id']));

        $paymentGatewayKey = $this->paymentGatewayKeyService->create($dto);

        return new PaymentGatewayKeyResource($paymentGatewayKey);
    }
}
