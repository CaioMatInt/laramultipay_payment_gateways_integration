<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewayKey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentGatewaysKeysFactory extends Factory
{
    protected $model = PaymentGatewayKey::class;

    public function definition()
    {
        return [
            'payment_gateway_id' => PaymentGateway::factory(),
            'company_id' => Company::factory(),
            'key' => $this->faker->uuid,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
