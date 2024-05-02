<?php

namespace App\DTOs\Authentication;

use App\Traits\DtoHandleDataTrait;

class LoginDto
{
    use DtoHandleDataTrait;

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
