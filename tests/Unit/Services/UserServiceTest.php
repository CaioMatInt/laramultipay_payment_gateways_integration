<?php

use App\DTOs\User\UserCreationDto;
use App\Models\User;
use App\Services\User\UserService;
use App\Services\UserType\UserTypeService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTypeTrait::class);

describe('UserServiceTest', function () {

    beforeEach(function () {

    });

    test('can create an user', function () {
        $userType = $this->createUserTypeCompanyAdmin();

        $userTypeService = Mockery::mock(UserTypeService::class);
        $userTypeService->shouldReceive('findCachedByName')->andReturn($userType);

        $userService = new UserService(
            new User(),
            $userTypeService
        );

        $userCreationDto = new UserCreationDto(
            [
                'name' => 'John Doe',
                'email' => 'john@doe.com',
                'password' => 'password'
            ]
        );

        $user = $userService->create($userCreationDto);

        expect($user->name)->toBe('John Doe')->and($user->email)->toBe('john@doe.com');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ]);
    });

    test('can find user provider by email', function () {
        $user = User::factory()->create();

        $user->load('provider');

        $userService = app(UserService::class);

        $provider = $userService->findUserProviderByEmail($user->email);

        expect($provider)->toBe($user->provider->name);
    });

    test('should return null when getting user provider by email when user does not have a provider', function () {
        $user = User::factory([
            'provider_id' => null,
            'external_provider_id' => null
        ])->create();

        $userService = app(UserService::class);

        $provider = $userService->findUserProviderByEmail($user->email);

        expect($provider)->toBeNull();
    });

    test('can find user by external provider id', function () {
        $user = User::factory()->create();

        $userService = app(UserService::class);

        $foundUser = $userService->findByExternalProviderId($user->external_provider_id);

        expect($foundUser->id)->toBe($user->id);
    });

    test('should return null when getting user by external provider id when user does not exist', function () {
        $userService = app(UserService::class);

        $foundUser = $userService->findByExternalProviderId('non-existing-external-provider-id');

        expect($foundUser)->toBeNull();
    });
});
