<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = [
            'google'
        ];

        foreach ($providers as $provider) {
            Provider::insert([
                'name' => $provider,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
