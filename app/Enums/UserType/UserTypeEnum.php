<?php

namespace App\Enums\UserType;

use App\Traits\EnumAttributeTrait;

enum UserTypeEnum : string
{
    use EnumAttributeTrait;

    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';
}
