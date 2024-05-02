<?php

namespace App\DTOs\Authentication;

class LoginDto
{
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

    /**
     * @param array{ email: string, password: string } $data
     */
    public static function fromRequest(array $data): self
    {
        return new self($data);
    }
}
