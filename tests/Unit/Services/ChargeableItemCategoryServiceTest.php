<?php

use App\Contracts\ChargeableItemCategory\ChargeableItemCategoryStorableInterface;
use App\Contracts\ChargeableItemCategory\ChargeableItemCategoryUpdatableInterface;
use App\Contracts\ModelAware;
use App\DTOs\ChargeableItemCategory\ChargeableItemCategoryDto;
use App\Models\ChargeableItemCategory;
use App\Services\ChargeableItemCategory\ChargeableItemCategoryService;
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

describe('ChargeableItemCategoryServiceTest', function () {

    beforeEach(function () {
        $this->chargeableItemCategoryService = app(ChargeableItemCategoryService::class);
        test()->mockCompanyAdminUser();
    });

    test('ensure that its using the DtoUpdatableTrait', function () {
        expect(in_array(DtoUpdatableTrait::class, class_uses_recursive($this->chargeableItemCategoryService)))
            ->toBeTrue();
    });

    test('ensure that its using the DestroyableTrait', function () {
        expect(in_array(DestroyableTrait::class, class_uses_recursive($this->chargeableItemCategoryService)))
            ->toBeTrue();
    });

    test('ensure that its using the PaginatorByCompanyTrait', function () {
        expect(in_array(PaginatorByCompanyTrait::class, class_uses_recursive($this->chargeableItemCategoryService)))
            ->toBeTrue();
    });

    test('ensure that its using the CacheableFinderTrait', function () {
        expect(in_array(CacheableFinderTrait::class, class_uses_recursive($this->chargeableItemCategoryService)))
            ->toBeTrue();
    });

    test('should implement ChargeableItemCategoryUpdatableInterface interface', function () {
        expect($this->chargeableItemCategoryService instanceof ChargeableItemCategoryUpdatableInterface)
            ->toBeTrue();
    });

    test('should implement ChargeableItemCategoryStorableInterface interface', function () {
        expect($this->chargeableItemCategoryService instanceof ChargeableItemCategoryStorableInterface)
            ->toBeTrue();
    });

    test('ensure that its using the DtoStorableTrait', function () {
        expect(in_array(DtoStorableTrait::class, class_uses_recursive($this->chargeableItemService)))
            ->toBeTrue();
    });

    test('should implement ModelAware interface', function () {
        expect($this->chargeableItemCategoryService instanceof ModelAware)
            ->toBeTrue();
    });

    test('should be using storeWithDtoAndAuthUserCompanyId to create a new chargeable item category', function () {
        $this->actingAs($this->userCompanyAdmin);

        $dto = new ChargeableItemCategoryDto([]);

        $mockService = Mockery::mock(ChargeableItemCategoryService::class)->makePartial();
        $expectedResult = new ChargeableItemCategory();

        $mockService->shouldReceive('storeWithDtoAndAuthUserCompanyId')
            ->once()
            ->andReturn($expectedResult);

        $result = $mockService->store($dto);

        expect($result)->toBe($expectedResult);
    });

    test('should be using updateWithDto to update a chargeable item category', function () {
        $this->actingAs($this->userCompanyAdmin);

        $id = 1;
        $dto = new ChargeableItemCategoryDto([]);

        $mockService = Mockery::mock(ChargeableItemCategoryService::class)->makePartial();
        $expectedResult = new ChargeableItemCategory();

        $mockService->shouldReceive('updateWithDto')
            ->once()
            ->with($id, $dto)
            ->andReturn($expectedResult);

        $result = $mockService->update($id, $dto);

        expect($result)->toBe($expectedResult);
    });

    test('should return the correct cache key', function () {
        $id = 1;
        $expectedCacheKey = config('cache_keys.chargeable_item_categories.by_id') . $id;

        $method = $this->invokeProtectedOrPrivateMethod(
            $this->chargeableItemCategoryService,
            'getFindCacheKey',
            [$id]
        );

        expect($method)->toBe($expectedCacheKey);
    });
});
