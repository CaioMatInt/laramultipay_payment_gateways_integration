<?php

namespace App\DTOs\User;

use App\Traits\DtoHandleDataTrait;

class UserCreationDto
{
    use DtoHandleDataTrait;

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
