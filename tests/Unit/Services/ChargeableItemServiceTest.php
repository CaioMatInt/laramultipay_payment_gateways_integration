<?php

use App\Contracts\ChargeableItem\ChargeableItemUpdatableInterface;
use App\Contracts\ModelAware;
use App\DTOs\ChargeableItem\ChargeableItemDto;
use App\Models\ChargeableItem;
use App\Services\ChargeableItem\ChargeableItemService;
use App\Traits\Database\CacheableFinderTrait;
use App\Traits\Database\DestroyableTrait;
use App\Traits\Database\DtoStorableTrait;
use App\Traits\Database\DtoUpdatableTrait;
use App\Traits\Database\PaginatorByCompanyTrait;
use Tests\Traits\ClassReflectionTrait;
use Tests\Traits\UserTrait;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(UserTrait::class);
uses(ClassReflectionTrait::class);

describe('chargeableItemServiceTest', function () {

    beforeEach(function () {
        $this->chargeableItemService = app(ChargeableItemService::class);
        test()->mockCompanyAdminUser();
    });

    test('ensure that its using the DtoUpdatableTrait', function () {
        expect(in_array(DtoUpdatableTrait::class, class_uses_recursive($this->chargeableItemService)))
            ->toBeTrue();
    });

    test('ensure that its using the DestroyableTrait', function () {
        expect(in_array(DestroyableTrait::class, class_uses_recursive($this->chargeableItemService)))
            ->toBeTrue();
    });

    test('ensure that its using the PaginatorByCompanyTrait', function () {
        expect(in_array(PaginatorByCompanyTrait::class, class_uses_recursive($this->chargeableItemService)))
            ->toBeTrue();
    });

    test('ensure that its using the CacheableFinderTrait', function () {
        expect(in_array(CacheableFinderTrait::class, class_uses_recursive($this->chargeableItemService)))
            ->toBeTrue();
    });

    test('ensure that its using the DtoStorableTrait', function () {
        expect(in_array(DtoStorableTrait::class, class_uses_recursive($this->chargeableItemService)))
            ->toBeTrue();
    });

    test('should implement ChargeableItemUpdatableInterface interface', function () {
        expect($this->chargeableItemService instanceof ChargeableItemUpdatableInterface)
            ->toBeTrue();
    });

    test('should implement ModelAware interface', function () {
        expect($this->chargeableItemService instanceof ModelAware)
            ->toBeTrue();
    });

    test('should be using storeWithDtoAndAuthUserCompanyId to create a new chargeable item', function () {
        $this->actingAs($this->userCompanyAdmin);

        $dto = new ChargeableItemDto([]);

        $mockService = Mockery::mock(ChargeableItemService::class)->makePartial();
        $expectedResult = new ChargeableItem();

        $mockService->shouldReceive('storeWithDtoAndAuthUserCompanyId')
            ->once()
            ->andReturn($expectedResult);

        $result = $mockService->store($dto);

        expect($result)->toBe($expectedResult);
    });

    test('should be using updateWithDto to update a chargeable item', function () {
        $this->actingAs($this->userCompanyAdmin);

        $id = 1;
        $dto = new ChargeableItemDto([]);

        $mockService = Mockery::mock(ChargeableItemService::class)->makePartial();
        $expectedResult = new ChargeableItem();

        $mockService->shouldReceive('updateWithDto')
            ->once()
            ->andReturn($expectedResult);

        $result = $mockService->update($id, $dto);

        expect($result)->toBe($expectedResult);
    });

    test('should return the correct cache key', function () {
        $id = 1;
        $expectedCacheKey = config('cache_keys.chargeable_item.by_id') . $id;

        $method = $this->invokeProtectedOrPrivateMethod(
            $this->chargeableItemService,
            'getFindCacheKey',
            [$id]
        );

        expect($method)->toBe($expectedCacheKey);
    });
});
