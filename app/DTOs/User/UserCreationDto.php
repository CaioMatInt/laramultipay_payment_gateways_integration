<?php

namespace App\DTOs\User;

use App\DTOs\BaseDto;

class UserCreationDto extends BaseDto
{
    public string $name;
    public string $email;
    public string $password;

    /**
     * @param array{ name: string, email: string, password: string } $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}
