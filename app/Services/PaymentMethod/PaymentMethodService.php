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
        return Cache::rememberForever('payment_method_' . $name, function () use ($name) {
            return $this->model->where('name', $name)->firstOrFail();
        });
    }

    public function findCached(int $id): PaymentMethod
    {
        return Cache::rememberForever('payment_method_' . $id, function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }
}
