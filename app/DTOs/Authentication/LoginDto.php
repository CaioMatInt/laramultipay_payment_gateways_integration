<?php

namespace App\DTOs\Authentication;

use App\Traits\Dto\RequestDataBinderTrait;

class LoginDto
{
    use RequestDataBinderTrait;

    public string $email;
    public string $password;

    /**
     * @param array{ email: string, password: string } $data
     */
    public function __construct(array $data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}
