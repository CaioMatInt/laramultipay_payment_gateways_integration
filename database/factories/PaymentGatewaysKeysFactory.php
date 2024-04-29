<?php

namespace Database\Factories;

use App\Models\PaymentGatewayKey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentGatewaysKeysFactory extends Factory
{
    protected $model = PaymentGatewayKey::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
