<?php

namespace App\Services\Authentication;

use App\Models\Provider;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProviderService
{

    public function __construct(
        private User $userModel,
        private SocialiteService $socialiteService,
        private UserService $userService
    ) { }

    public function redirect(string $providerName): RedirectResponse
    {
        return $this->socialiteService->redirect($providerName);
    }

    /**
     * @throws \Exception
     */
    public function authenticateAndLogin(string $providerName): void
    {
        $providerSocialiteUser = $this->socialiteService->login($providerName);

        $user = $this->findOrCreateUserFromProviderData($providerSocialiteUser, $providerName);

        Auth::login($user, true);
    }

    protected function findOrCreateUserFromProviderData(object $providerSocialiteUser, string $providerName): User
    {
        $user = User::whereExternalProviderId($providerSocialiteUser->id)->first();

        if (!$user) {
            $this->userService->checkProviderMatchOrThrow($providerSocialiteUser->email, $providerName);
            $user = $this->createUserFromProviderData($providerSocialiteUser, $providerName);
        }

        return $user;
    }

    protected function createUserFromProviderData(object $providerSocialiteUser, string $providerName): User
    {
        return $this->userModel->create([
            'name' => $providerSocialiteUser->name ?? $providerSocialiteUser->nickname,
            'email' => $providerSocialiteUser->email,
            'provider_id' => Provider::selectIdByName($providerName)->firstOrFail()->id,
            'external_provider_id' => $providerSocialiteUser->id,
        ]);
    }
}
