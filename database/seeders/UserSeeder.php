<?php

namespace Database\Seeders;

use App\Enums\UserType\UserTypeEnum;
use App\Models\Company;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUserTypeId = UserType::whereName(UserTypeEnum::SUPER_ADMIN->value)->first()->id;

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john_doe@gmail.com',
            'password' => bcrypt(123456),
            'user_type_id' => $adminUserTypeId,
            'company_id' => Company::first()->id,
        ]);
    }
}
