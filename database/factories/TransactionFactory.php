<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'gateway_transaction_id' => $this->faker->word(),
            'gateway_status' => $this->faker->word(),
            'response_code' => $this->faker->word(),
            'date' => Carbon::now(),
        ];
    }
}
