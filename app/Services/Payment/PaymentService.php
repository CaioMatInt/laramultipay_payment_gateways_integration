<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentCreationDto;
use App\Models\Payment;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Database\Eloquent\Collection;

class PaymentService
{
    public function __construct(
        private readonly Payment $model,
        private readonly PaymentGenericStatusService $paymentGenericStatusService,
        private readonly PaymentMethodService $paymentMethodService,
    ) { }

    public function getByCompanyId(int $companyId): Collection
    {
        return $this->model->where('company_id', $companyId)->get();
    }

    public function create(PaymentCreationDto $dto): Payment
    {
        $data['user_id'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->company_id;
        $data['payment_generic_status_id'] = $this->paymentGenericStatusService->getCachedInitialStatus()->id;
        $data['payment_method_id'] = $this->paymentMethodService->findCachedByName($dto->payment_method)->id;
        $data['amount'] = $dto->amount;
        $data['currency'] = $dto->currency;
        return $this->model->create($data);
    }
}
