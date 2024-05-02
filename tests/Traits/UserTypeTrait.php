<?php

namespace Tests\Traits;

use App\Enums\UserType\UserTypeEnum;
use App\Models\UserType;

trait UserTypeTrait
{
    public function createUserTypeCompanyAdmin(): UserType
    {
        return UserType::factory()->create([
            'name' => UserTypeEnum::COMPANY_ADMIN->value
        ]);
    }
}
