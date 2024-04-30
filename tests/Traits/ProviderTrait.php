<?php

namespace Tests\Traits;

use App\Models\Provider;

trait ProviderTrait
{
    private Provider $googleProvider;
    private Provider $facebookProvider;
    private Provider $githubProvider;

    private function mockGoogleProvider(): void
    {
        $this->googleProvider = Provider::factory()->create(['name' => 'google']);
    }

    private function mockFacebookProvider(): void
    {
        $this->facebookProvider = Provider::factory()->create(['name' => 'facebook']);
    }

    private function mockGithubProvider(): void
    {
        $this->githubProvider = Provider::factory()->create(['name' => 'github']);
    }

    private function mockProviders(): void
    {
        $this->mockGoogleProvider();
        $this->mockFacebookProvider();
        $this->mockGithubProvider();
    }
}
