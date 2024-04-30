<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod\PaymentMethodEnum;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        foreach(PaymentMethodEnum::values() as $name) {
            PaymentMethod::create([
                'name' => $name
            ]);
        }
    }
}
