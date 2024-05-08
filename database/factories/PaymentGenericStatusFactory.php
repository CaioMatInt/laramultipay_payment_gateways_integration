<?php

namespace Database\Factories;

use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Models\PaymentGenericStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentGenericStatusFactory extends Factory
{
    protected $model = PaymentGenericStatus::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->unique()->word(),
        ];
    }
}
