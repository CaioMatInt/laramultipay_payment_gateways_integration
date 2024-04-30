<?php

namespace Database\Seeders;

use App\Enums\UserType\UserTypeEnum;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $defaultTypeNames = UserTypeEnum::values();
        foreach ($defaultTypeNames as $typeName) {
            UserType::factory()->create(['name' => $typeName]);
        }
    }
}
