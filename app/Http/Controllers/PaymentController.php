<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\Payment\PaymentService;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;

class PaymentController extends Controller
{

    public function __construct(private readonly PaymentService $service)
    {
    }

    public function store(StorePaymentRequest $request)
    {
        //@@TODO: Must handle exceptions
        $payment = $this->service->create($request->validated());

        return new PaymentResource(
            $payment,
            app(PaymentGenericStatusService::class),
            app(PaymentMethodService::class)
        );
    }
}
