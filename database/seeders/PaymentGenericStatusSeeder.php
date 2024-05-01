<?php

namespace Database\Seeders;

use App\Models\PaymentGenericStatus;
use Illuminate\Database\Seeder;

class PaymentGenericStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'pending',
            'completed',
            'failed',
            'cancelled',
            'refunded',
            'disputed'
        ];

        foreach ($statuses as $status) {
            PaymentGenericStatus::factory()->create([
                'name' => $status
            ]);
        }
    }
}
