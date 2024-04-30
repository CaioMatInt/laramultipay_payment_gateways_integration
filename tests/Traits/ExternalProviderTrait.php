<?php

namespace Tests\Traits;

trait ExternalProviderTrait
{
    private object $googleResponse;
    private object $facebookResponse;
    private object $githubResponse;

    private function mockGoogleResponse(): void
    {
        $googleResponse = file_get_contents(base_path
            ('tests/Mocks/Authentication/google_provider_authentication_response.json')
        );

        $this->googleResponse = json_decode($googleResponse);
    }

    private function mockFacebookResponse(): void
    {
        $facebookResponse = file_get_contents(base_path
            ('tests/Mocks/Authentication/facebook_provider_authentication_response.json')
        );

        $this->facebookResponse = json_decode($facebookResponse);
    }

    private function mockGithubResponse(): void
    {
        $githubResponse = file_get_contents(base_path
            ('tests/Mocks/Authentication/github_provider_authentication_response.json')
        );

        $this->githubResponse = json_decode($githubResponse);
    }

    private function mockExternalProviderResponses(): void
    {
        $this->mockGoogleResponse();
        $this->mockFacebookResponse();
        $this->mockGithubResponse();
    }
}
