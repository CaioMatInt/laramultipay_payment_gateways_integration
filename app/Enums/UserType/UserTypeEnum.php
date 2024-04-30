<?php

namespace App\Enums\UserType;

use App\Traits\EnumAttributeTrait;

enum UserTypeEnum : string
{
    use EnumAttributeTrait;

    case ADMIN = 'admin';
    case USER = 'user';
}
