<?php

namespace Database\Factories;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\Company;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\PaymentGenericStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->word(),
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'amount' => $this->faker->randomNumber(),
            'currency' => $this->faker->randomElement(PaymentCurrencyEnum::values()),
            'payment_generic_status_id' => PaymentGenericStatus::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'payment_gateway_id' => PaymentGateway::factory(),
            'expires_at' => Carbon::now()->addDays(1),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
