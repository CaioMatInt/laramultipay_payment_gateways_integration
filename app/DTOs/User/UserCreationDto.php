<?php

namespace App\DTOs\User;

class UserCreationDto
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public static function fromRequest(array $data): self
    {
        return new self($data);
    }
}
