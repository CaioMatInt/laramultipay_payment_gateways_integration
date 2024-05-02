<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;

class PaymentService
{
    public function __construct(
        private readonly Payment $model,
        private readonly PaymentGenericStatusService $paymentGenericStatusService,
        private readonly PaymentMethodService $paymentMethodService,
    ) { }

    public function create(array $data): Payment
    {
        $data['user_id'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->company_id;
        $data['payment_generic_status_id'] = $this->paymentGenericStatusService->getCachedInitialStatus()->id;
        $data['payment_method_id'] = $this->paymentMethodService->findCachedByName($data['payment_method'])->id;
        return $this->model->create($data);
    }
}
