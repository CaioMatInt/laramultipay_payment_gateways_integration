<?php

namespace Tests\Traits;

use App\Services\Authentication\SocialiteService;

trait SocialiteTrait
{
    private function makeSocialiteServiceStub(string $method, $return)
    {
        $socialiteServiceStub = $this->createStub(SocialiteService::class);
        $socialiteServiceStub->method($method)
            ->willReturn($return);
        app()->instance(SocialiteService::class, $socialiteServiceStub);
    }

    /**
     * $responses expected format:
     * https://phpunit.de/manual/current/en/test-doubles.html#test-doubles.stubs.examples.StubTest5.php
     */
    private function makeSocialiteServiceStubWithMultipleResponses(string $method, array $responses)
    {
        $socialiteServiceStub = $this->createStub(SocialiteService::class);

        $socialiteServiceStub->method($method)
            ->willReturnMap($responses);

        app()->instance(SocialiteService::class, $socialiteServiceStub);
    }
}
