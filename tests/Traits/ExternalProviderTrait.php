<?php

namespace Tests\Traits;

trait ExternalProviderTrait
{
    public object $googleResponse;
    public object $facebookResponse;
    public object $githubResponse;

    public function mockGoogleResponse(): void
    {
        $googleResponse = file_get_contents(base_path
            ('tests/Mocks/Authentication/google_provider_authentication_response.json')
        );

        $this->googleResponse = json_decode($googleResponse);
    }

    public function mockFacebookResponse(): void
    {
        $facebookResponse = file_get_contents(base_path
            ('tests/Mocks/Authentication/facebook_provider_authentication_response.json')
        );

        $this->facebookResponse = json_decode($facebookResponse);
    }

    public function mockGithubResponse(): void
    {
        $githubResponse = file_get_contents(base_path
            ('tests/Mocks/Authentication/github_provider_authentication_response.json')
        );

        $this->githubResponse = json_decode($githubResponse);
    }

    public function mockExternalProviderResponses(): void
    {
        $this->mockGoogleResponse();
        $this->mockFacebookResponse();
        $this->mockGithubResponse();
    }
}
