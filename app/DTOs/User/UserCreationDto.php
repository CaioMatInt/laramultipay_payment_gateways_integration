<?php

namespace App\DTOs\User;

use App\DTOs\BaseDto;

class UserCreationDto extends BaseDto
{
    public string $name;
    public string $email;
    public string $password;
}
