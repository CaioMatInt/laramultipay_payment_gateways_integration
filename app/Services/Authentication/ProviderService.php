<?php

namespace App\Services\Authentication;

use App\Models\Provider;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $user = $this->userService->findByExternalProviderId($providerSocialiteUser->id);

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
            'provider_id' => $this->getIdByName($providerName),
            'external_provider_id' => $providerSocialiteUser->id,
        ]);
    }

    public function getIdByName(string $name): int
    {
        return Cache::rememberForever('provider_id_' . $name, function () use ($name) {
            return Provider::getIdByName($name)->firstOrFail()->id;
        });
    }
}
