<?php

namespace App\Services\PaymentGateway;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayService
{
    public function __construct(
        private readonly PaymentGateway $model,
    ) { }

    public function findCached(int $id): PaymentGateway
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_gateway.by_id') . $id, function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }

    public function findCachedByName(string $name): PaymentGateway
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_gateway.by_name') . $name, function () use ($name) {
            return $this->model->where('name', $name)->firstOrFail();
        });
    }
}
