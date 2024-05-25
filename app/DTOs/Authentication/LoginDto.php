<?php

namespace App\DTOs\Authentication;

use App\DTOs\BaseDto;

class LoginDto extends BaseDto
{
    public string $email;
    public string $password;
}
