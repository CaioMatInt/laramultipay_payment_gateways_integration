<?php

namespace App\Services\User;

use App\DTOs\Authentication\LoginDto;
use App\DTOs\User\UserCreationDto;
use App\Enums\UserType\UserTypeEnum;
use App\Exceptions\Authentication\ProviderMismatchException;
use App\Models\User;
use App\Models\UserType;
use App\Services\UserType\UserTypeService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        private readonly User $model,
        private readonly UserTypeService $userTypeService
    ) { }

    public function login(LoginDto $dto): void
    {
        $authAttemptWasSuccessful = Auth::attempt(['email' => $dto->email, 'password' => $dto->password]);

        if (!$authAttemptWasSuccessful) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }
    }

    public function createUserToken(): string
    {
        return auth()->user()->createToken('LaravelSanctumAuth')->plainTextToken;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function sendPasswordResetLinkEmail(string $email): string
    {
        $status = Password::sendResetLink(
            ['email' => $email],
            function () {
                route('user.password.reset');
            }
        );

        if ($status === Password::RESET_LINK_SENT) {
            return __($status);
        }

        throw ValidationException::withMessages([
            'email' => __($status)
        ]);
    }

    public function resetPassword(string $email, string $password, string $passwordConfirmation, string $token): string
    {
        $status = Password::reset([
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
            'token' => $token
        ], function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return __($status);
        }

        throw ValidationException::withMessages([
            'email' => __($status)
        ]);
    }

    /**
     * @throws \Exception
     */
    public function checkProviderMatchOrThrow(string $userEmail, string $providerName): void
    {
        $userProviderName = $this->findUserProviderByEmail($userEmail);

        if ($userProviderName && $userProviderName !== $providerName) {
            throw new ProviderMismatchException($userEmail, $providerName);
        }
    }

    public function create(UserCreationDto $dto): User
    {
        $companyAdminUserTypeId = $this->userTypeService->findCachedByName(UserTypeEnum::COMPANY_ADMIN->value)->id;

        $data = [
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
            'user_type_id' => $companyAdminUserTypeId
        ];

        return $this->model->create($data);
    }

    public function findUserProviderByEmail(string $email): ?string
    {
        $user = $this->model->select(['id', 'provider_id'])
            ->where('email', $email)
            ->with(['provider:id,name'])
            ->first();

        return $user->provider->name ?? null;
    }

    public function findByExternalProviderId(string $externalProviderId): ?User
    {
        return $this->model->where('external_provider_id', $externalProviderId)->first();
    }
}
