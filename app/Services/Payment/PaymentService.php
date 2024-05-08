<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentCreationDto;
use App\Exceptions\PaymentGateway\InvalidOrNonRedirectablePaymentGatewayException;
use App\Factories\PaymentServiceFactory;
use App\Models\Payment;
use App\Services\PaymentGateway\PaymentGatewayService;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private readonly Payment $model,
        private readonly PaymentGenericStatusService $paymentGenericStatusService,
        private readonly PaymentMethodService $paymentMethodService,
        private readonly PaymentGatewayService $paymentGatewayService
    ) { }

    public function getPaginatedByCompanyId(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        //@@TODO: Add caching
        return $this->model->where('company_id', $companyId)->paginate($perPage);
    }

    public function create(PaymentCreationDto $dto): Payment
    {
        $data['uuid'] = Str::uuid();
        $data['name'] = $dto->name;
        $data['user_id'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->company_id;
        $data['payment_generic_status_id'] = $this->paymentGenericStatusService->getCachedInitialStatus()->id;
        $data['payment_method_id'] = $this->paymentMethodService->findCachedByName($dto->paymentMethod)->id;
        $data['amount'] = $dto->amount;
        $data['currency'] = $dto->currency;
        $data['expires_at'] = $dto->expiresAt ?? null;
        if ($dto->paymentGateway) {
            $data['payment_gateway_id'] = $this->paymentGatewayService->findCachedByName($dto->paymentGateway)->id;
        }
        return $this->model->create($data);
    }

    public function findCached(string $uuid): Payment
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment.by_uuid') . $uuid, function () use ($uuid) {
            return $this->model->where('uuid', $uuid)->firstOrFail();
        });
    }

    /**
     * @throws InvalidOrNonRedirectablePaymentGatewayException
     */
    public function redirectToGatewayPaymentPage(string $paymentUuid): RedirectResponse
    {
        $payment = $this->findCached($paymentUuid);

        if (!$payment || !$payment->payment_gateway_id) {
            abort(404);
        }

        $paymentGateway = $this->paymentGatewayService->findCached($payment->payment_gateway_id);

        $paymentServiceFactory = new PaymentServiceFactory();
        $gatewayService = $paymentServiceFactory->create($paymentGateway->name);
        return $gatewayService->redirectToPaymentPage($payment);
    }
}
