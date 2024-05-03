<?php

namespace App\Http\Controllers;

use App\DTOs\Payment\PaymentCreationDto;
use App\Http\Requests\Payment\PaymentIndexRequest;
use App\Http\Requests\Payment\ShowPaymentRequest;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\Payment\PaymentResource;
use App\Services\Payment\PaymentService;

class PaymentController extends Controller
{
    CONST INDEX_DEFAULT_PER_PAGE = 15;

    public function __construct(private readonly PaymentService $service)
    {
    }

    public function index(PaymentIndexRequest $request)
    {
        $payments = $this->service->getByCompanyId(
            auth()->user()->company_id,
            $request->perPage ?? self::INDEX_DEFAULT_PER_PAGE
        );

        return PaymentResource::collection($payments);
    }

    //@@TODO: migrate from id to UUID
    public function show(ShowPaymentRequest $request, int $id): PaymentResource
    {
        $payment = $this->service->findCached($id);

        return new PaymentResource(
            $payment
        );
    }

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $paymentCreationDto = PaymentCreationDto::fromRequest($request->only(['amount', 'currency', 'payment_method']));
        $payment = $this->service->create($paymentCreationDto);

        return new PaymentResource(
            $payment
        );
    }
}
