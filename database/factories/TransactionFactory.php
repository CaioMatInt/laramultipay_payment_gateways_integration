<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Payment;
use App\Models\PaymentGatewayTransactionStatus;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'payment_gateway_transaction_status_id' => PaymentGatewayTransactionStatus::factory(),
            'company_id' => Company::factory(),
            'gateway_transaction_id' => $this->faker->uuid(),
            'gateway_status' => $this->faker->word(),
            'response_code' => $this->faker->word(),
            'date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
