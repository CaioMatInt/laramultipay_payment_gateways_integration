<?php

namespace App\DTOs\User;

class UserCreationDto
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

    /**
     * @param array{ name: string, email: string, password: string } $data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        return new self($data);
    }
}
