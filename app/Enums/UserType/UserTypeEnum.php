<?php

namespace App\Enums\UserType;

use App\Traits\Enum\EnumAttributeTrait;

enum UserTypeEnum : string
{
    use EnumAttributeTrait;

    case SUPER_ADMIN = 'super_admin';
    case COMPANY_ADMIN = 'company_admin';
    case USER = 'user';
}
