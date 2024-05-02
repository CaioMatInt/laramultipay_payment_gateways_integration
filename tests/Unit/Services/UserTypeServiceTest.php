<?php

use App\Models\UserType;
use App\Services\UserType\UserTypeService;
use Illuminate\Support\Facades\Cache;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);
uses(\Tests\Traits\PaymentTrait::class);

describe('UserTypeServiceTest', function () {

    beforeEach(function () {
        $this->userTypeService = app(UserTypeService::class);
    });

    test('can find by name', function () {
        $userTypeFactory = UserType::factory()->create();

        $userType = $this->userTypeService->findCachedByName($userTypeFactory->name);

        expect($userType->id)->toBe($userTypeFactory->id)
            ->and($userType->name)->toBe($userTypeFactory->name);
    });

    test('should cache find by name result', function () {
        $userTypeFactory = UserType::factory()->create();

        $this->userTypeService->findCachedByName($userTypeFactory->name);

        expect(Cache::has('user_type_' . $userTypeFactory->name))->toBeTrue();

        $cachedUserType = Cache::get('user_type_' . $userTypeFactory->name);
        expect($cachedUserType->id)->toBe($userTypeFactory->id)
            ->and($cachedUserType->name)->toBe($userTypeFactory->name);
    });
});
