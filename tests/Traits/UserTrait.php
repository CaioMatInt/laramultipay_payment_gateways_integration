<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    private User $user;
    private User $userWithoutUnverifiedEmail;
    private string $unhashedDefaultUserPassword = '12345678';

    private function mockUser(): void
    {
        $this->user = User::factory()->create([
            'password' => Hash::make($this->unhashedDefaultUserPassword)
        ]);
    }

    private function mockUserWithUnverifiedEmail(): void
    {
        $this->userWithoutUnverifiedEmail = User::factory()->create([
            'password' => Hash::make($this->unhashedDefaultUserPassword),
            'email_verified_at' => null
        ]);
    }

    private function getDefaultPasswordAndConfirmationPassword(): array
    {
        return [
            'password' => $this->unhashedDefaultUserPassword,
            'password_confirmation' => $this->unhashedDefaultUserPassword
        ];
    }
}
