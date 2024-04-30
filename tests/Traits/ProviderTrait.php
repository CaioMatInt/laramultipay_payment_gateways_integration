<?php

namespace Tests\Traits;

use App\Models\Provider;

trait ProviderTrait
{
    public Provider $googleProvider;
    public Provider $facebookProvider;
    public Provider $githubProvider;

    public function mockGoogleProvider(): void
    {
        $this->googleProvider = Provider::factory()->create(['name' => 'google']);
    }

    public function mockFacebookProvider(): void
    {
        $this->facebookProvider = Provider::factory()->create(['name' => 'facebook']);
    }

    public function mockGithubProvider(): void
    {
        $this->githubProvider = Provider::factory()->create(['name' => 'github']);
    }

    public function mockProviders(): void
    {
        $this->mockGoogleProvider();
        $this->mockFacebookProvider();
        $this->mockGithubProvider();
    }
}
