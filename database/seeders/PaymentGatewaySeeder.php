<?php

namespace Database\Seeders;

use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        foreach(PaymentGatewayEnum::values() as $name) {
            PaymentGateway::create([
                'name' => $name
            ]);
        }
    }
}
