<?php

namespace App\Services\PaymentGateway;

use App\Contracts\ModelAware;
use App\Models\PaymentGateway;
use App\Traits\Database\CacheableFinderByNameTrait;
use App\Traits\Database\CacheableFinderTrait;

class PaymentGatewayService implements ModelAware
{
    use CacheableFinderTrait, CacheableFinderByNameTrait;

    public function __construct(
        private readonly PaymentGateway $model,
    ) { }

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.payment_gateway.by_id') . $id;
    }

    public function getFindByNameCacheKey(string $name): string
    {
        return config('cache_keys.payment_gateway.by_name') . $name;
    }
}
