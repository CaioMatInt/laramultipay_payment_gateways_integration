<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentCreationDto;
use App\Models\Payment;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private readonly Payment $model,
        private readonly PaymentGenericStatusService $paymentGenericStatusService,
        private readonly PaymentMethodService $paymentMethodService,
    ) { }

    public function getPaginatedByCompanyId(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        //@@TODO: Add caching
        return $this->model->where('company_id', $companyId)->paginate($perPage);
    }

    public function create(PaymentCreationDto $dto): Payment
    {
        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->company_id;
        $data['payment_generic_status_id'] = $this->paymentGenericStatusService->getCachedInitialStatus()->id;
        $data['payment_method_id'] = $this->paymentMethodService->findCachedByName($dto->payment_method)->id;
        $data['amount'] = $dto->amount;
        $data['currency'] = $dto->currency;
        return $this->model->create($data);
    }

    public function findCached(string $uuid): Payment
    {
        return Cache::rememberForever("payment.{$uuid}", function () use ($uuid) {
            return $this->model->where('uuid', $uuid)->firstOrFail();
        });
    }
}
