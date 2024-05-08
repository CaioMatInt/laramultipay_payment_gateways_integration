<?php

namespace App\Http\Controllers;

use App\DTOs\Payment\PaymentCreationDto;
use App\Exceptions\PaymentGateway\InvalidOrNonRedirectablePaymentGatewayException;
use App\Http\Requests\Payment\PaymentIndexRequest;
use App\Http\Requests\Payment\ShowPaymentRequest;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\Payment\PaymentResource;
use App\Services\Payment\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    CONST INDEX_DEFAULT_PER_PAGE = 15;

    public function __construct(private readonly PaymentService $service)
    {
    }

    public function index(PaymentIndexRequest $request)
    {
        $payments = $this->service->getPaginatedByCompanyId(
            auth()->user()->company_id,
            $request->perPage ?? self::INDEX_DEFAULT_PER_PAGE
        );

        return PaymentResource::collection($payments);
    }

    public function show(ShowPaymentRequest $request, string $uuid): PaymentResource
    {
        $payment = $this->service->findCached($uuid);

        return new PaymentResource(
            $payment
        );
    }

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $paymentCreationData = $request->only(['name', 'amount', 'currency', 'payment_method', 'payment_gateway']);
        $paymentCreationData['expires_at'] = Carbon::parse($request->expires_at);
        $paymentCreationDto = PaymentCreationDto::fromRequest($paymentCreationData);

        $payment = $this->service->create($paymentCreationDto);

        return new PaymentResource(
            $payment
        );
    }

    /**
     * @throws InvalidOrNonRedirectablePaymentGatewayException
     */
    public function redirectToGatewayPaymentPage(): RedirectResponse
    {
        return $this->service->redirectToGatewayPaymentPage(request()->route('uuid'));
    }
}
