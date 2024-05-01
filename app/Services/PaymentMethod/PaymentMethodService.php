<?php

namespace App\Services\PaymentMethod;

use App\Models\PaymentMethod;

class PaymentMethodService
{
    public function __construct(
        private readonly PaymentMethod $model,
    ) { }

    public function findByName(string $name): PaymentMethod
    {
        //@@TODO add caching
        return $this->model->whereName($name)->first();
    }

    public function find(int $id): PaymentMethod
    {
        //@@TODO add caching
        return $this->model->findOrFail($id);
    }
}
