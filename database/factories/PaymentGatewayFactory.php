<?php

namespace Database\Factories;

use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Models\PaymentGateway;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentGatewayFactory extends Factory
{
    protected $model = PaymentGateway::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->unique()->word(),
        ];
    }
}
