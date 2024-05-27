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
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $service)
    {
    }

    public function index(PaymentIndexRequest $request): ResourceCollection
    {
        $payments = $this->service->getPaginatedByCompanyId(
            auth()->user()->company_id,
            $request->perPage ?? config('database.pagination.default_records_per_page')
        );

        return PaymentResource::collection($payments);
    }

    //@@TODO: Check if this Request is really necessary.
    public function show(ShowPaymentRequest $request): PaymentResource
    {
        $payment = $this->service->findCached($request->uuid);

        return new PaymentResource(
            $payment
        );
    }

    public function store(StorePaymentRequest $request): PaymentResource
    {
        $paymentCreationData = $request->only(['name', 'amount', 'currency', 'payment_method', 'payment_gateway']);
        $paymentCreationData['expires_at'] = Carbon::parse($request->expires_at);
        $paymentCreationDto = new PaymentCreationDto($paymentCreationData);

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
