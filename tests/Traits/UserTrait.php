<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    use UserTypeTrait;

    public User $user;
    public User $userWithUnverifiedEmail;
    public string $unhashedDefaultUserPassword = '12345678';
    public User $userCompanyAdmin;


    public function mockUser(): void
    {
        $this->user = User::factory()->create([
            'password' => Hash::make($this->unhashedDefaultUserPassword)
        ]);
    }

    public function mockUserWithUnverifiedEmail(): void
    {
        $this->userWithUnverifiedEmail = User::factory()->create([
            'password' => Hash::make($this->unhashedDefaultUserPassword),
            'email_verified_at' => null
        ]);
    }

    public function mockCompanyAdminUser(): void
    {
        $companyAdminUserType = $this->createCompanyAdmin();

        $this->userCompanyAdmin = User::factory()->create([
            'password' => Hash::make($this->unhashedDefaultUserPassword),
            'user_type_id' => $companyAdminUserType->id
        ]);
    }

    public function getDefaultPasswordAndConfirmationPassword(): array
    {
        return [
            'password' => $this->unhashedDefaultUserPassword,
            'password_confirmation' => $this->unhashedDefaultUserPassword
        ];
    }
}
