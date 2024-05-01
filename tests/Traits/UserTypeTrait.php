<?php

namespace Tests\Traits;

use App\Enums\UserType\UserTypeEnum;
use App\Models\UserType;

trait UserTypeTrait
{
    public function createCompanyAdmin(): UserType
    {
        return UserType::factory()->create([
            'name' => UserTypeEnum::COMPANY_ADMIN->value
        ]);
    }
}
