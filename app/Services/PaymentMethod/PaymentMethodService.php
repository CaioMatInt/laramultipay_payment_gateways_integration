<?php

namespace App\Services\PaymentMethod;

use App\Contracts\ModelAware;
use App\Models\PaymentMethod;
use App\Traits\Database\CacheableFinderByNameTrait;
use App\Traits\Database\CacheableFinderTrait;

class PaymentMethodService implements ModelAware
{
    use CacheableFinderTrait, CacheableFinderByNameTrait;

    public function __construct(
        private readonly PaymentMethod $model,
    ) { }

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.payment_method.by_id') . $id;
    }

    public function getFindByNameCacheKey(string $name): string
    {
        return config('cache_keys.payment_method.by_name') . $name;
    }
}
