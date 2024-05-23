<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewayKey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayKeyFactory extends Factory
{
    protected $model = PaymentGatewayKey::class;

    public function definition()
    {
        return [
            'payment_gateway_id' => PaymentGateway::factory(),
            'company_id' => Company::factory(),
            'key' => Crypt::encrypt($this->faker->uuid()),
            'type' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
