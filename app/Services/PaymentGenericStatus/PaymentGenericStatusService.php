<?php

namespace App\Services\PaymentGenericStatus;

use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Models\PaymentGenericStatus;
use Illuminate\Support\Facades\Cache;

class PaymentGenericStatusService
{
    public function __construct(
        private readonly PaymentGenericStatus $model
    ) { }

    public function getCachedInitialStatus(): PaymentGenericStatus
    {
        return Cache::rememberForever('payment_generic_status_initial', function () {
            return $this->model->whereName(PaymentGenericStatusEnum::PENDING->value)->first();
        });
    }

    public function findCached(int $id): PaymentGenericStatus
    {
        return Cache::rememberForever("payment_generic_status_{$id}", function () use ($id) {
            return $this->model->find($id);
        });
    }
}
