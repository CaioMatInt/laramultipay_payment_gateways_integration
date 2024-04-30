<?php

namespace Database\Factories;

use App\Models\PaymentGateway;
use App\Models\PaymentGatewayTransactionStatus;
use App\Models\PaymentGenericStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentGatewayTransactionStatusesFactory extends Factory
{
    protected $model = PaymentGatewayTransactionStatus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                    'pending',
                    'completed',
                    'failed',
                    'cancelled',
                    'refunded',
                    'disputed'
                ]
            ),
            'description' => $this->faker->sentence(),
            'payment_gateway_id' => PaymentGateway::factory(),
            'payment_generic_status_id' => PaymentGenericStatus::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
