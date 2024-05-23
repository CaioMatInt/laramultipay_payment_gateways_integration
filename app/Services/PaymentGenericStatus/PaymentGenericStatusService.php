<?php

namespace App\Services\PaymentGenericStatus;

use App\Contracts\ModelAware;
use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Models\PaymentGenericStatus;
use App\Traits\Database\CacheableFinderTrait;
use Illuminate\Support\Facades\Cache;

class PaymentGenericStatusService implements ModelAware
{

    use CacheableFinderTrait;

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

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.payment_generic_status.by_id') . $id;
    }
}
