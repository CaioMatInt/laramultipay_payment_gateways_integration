<?php

namespace App\Http\Controllers;

use App\DTOs\Payment\PaymentCreationDto;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\Payment\PaymentResource;
use App\Services\Payment\PaymentService;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;

class PaymentController extends Controller
{

    public function __construct(private readonly PaymentService $service)
    {
    }

    public function index()
    {

        $payments = $this->service->getByCompanyId(auth()->user()->company_id);

        return PaymentResource::collection($payments);
    }

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $paymentCreationDto = PaymentCreationDto::fromRequest($request->only(['amount', 'currency', 'payment_method']));
        $payment = $this->service->create($paymentCreationDto);

        return new PaymentResource(
            $payment,
            app(PaymentGenericStatusService::class),
            app(PaymentMethodService::class)
        );
    }
}
