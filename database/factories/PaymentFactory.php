<?php

namespace Database\Factories;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\Company;
use App\Models\Payment;
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
            'amount' => $this->faker->randomNumber(),
            'user' => User::factory(),
            'company' => Company::factory(),
            'currency' => $this->faker->unique()->randomElement(PaymentCurrencyEnum::values()),
            'payment_generic_status_id' => null,
            'payment_method_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
