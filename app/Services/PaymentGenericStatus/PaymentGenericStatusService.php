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
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_generic_status.initial'), function () {
            return $this->model->whereName(PaymentGenericStatusEnum::PENDING->value)->firstOrFail();
        });
    }

    public function findCached(int $id): PaymentGenericStatus
    {
        return Cache::rememberForever(config('cache_keys.payment_generic_status.by_id') . $id, function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }
}
