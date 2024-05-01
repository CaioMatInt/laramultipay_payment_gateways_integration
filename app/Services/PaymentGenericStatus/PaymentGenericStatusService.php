<?php

namespace App\Services\PaymentGenericStatus;

use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Models\PaymentGenericStatus;

class PaymentGenericStatusService
{
    public function __construct(
        private readonly PaymentGenericStatus $model
    ) { }

    public function getInitialStatus(): PaymentGenericStatus
    {
        return $this->model->whereName(PaymentGenericStatusEnum::PENDING->value)->first();
    }

    public function find(int $id): PaymentGenericStatus
    {
        return $this->model->findOrFail($id);
    }
}
