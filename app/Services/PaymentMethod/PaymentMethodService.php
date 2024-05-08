<?php

namespace App\Services\PaymentMethod;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;

class PaymentMethodService
{
    public function __construct(
        private readonly PaymentMethod $model,
    ) { }

    public function findCachedByName(string $name): PaymentMethod
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_method.by_name') . $name, function () use ($name) {
            return $this->model->where('name', $name)->firstOrFail();
        });
    }

    public function findCached(int $id): PaymentMethod
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_method.by_id') . $id, function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }
}
