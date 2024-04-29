<?php

namespace Database\Factories;

use App\Models\PaymentLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentLogsFactory extends Factory
{
    protected $model = PaymentLog::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'event_type' => $this->faker->word(),
            'details' => $this->faker->word(),
        ];
    }
}
