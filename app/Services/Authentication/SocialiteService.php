<?php

namespace App\Services\Authentication;


use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialiteService
{

    public function login(string $providerName): object
    {
        return Socialite::driver($providerName)->stateless()->user();
    }

    public function redirect(string $providerName): RedirectResponse
    {
        return Socialite::driver($providerName)->stateless()->redirect();
    }
}
