<?php

use App\Contracts\ChargeableItemPrice\ChargeableItemPriceStorableInterface;
use App\Contracts\ChargeableItemPrice\ChargeableItemPriceUpdatableInterface;
use App\Contracts\ModelAware;
use App\DTOs\ChargeableItemPrice\ChargeableItemPriceDto;
use App\Models\ChargeableItemPrice;
use App\Services\ChargeableItemPrice\ChargeableItemPriceService;
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

describe('ChargeableItemPriceServiceTest', function () {

    beforeEach(function () {
        $this->chargeableItemPriceService = app(ChargeableItemPriceService::class);
        test()->mockCompanyAdminUser();
    });

    test('ensure that its using the DtoUpdatableTrait', function () {
        expect(in_array(DtoUpdatableTrait::class, class_uses_recursive($this->chargeableItemPriceService)))
            ->toBeTrue();
    });

    test('ensure that its using the DestroyableTrait', function () {
        expect(in_array(DestroyableTrait::class, class_uses_recursive($this->chargeableItemPriceService)))
            ->toBeTrue();
    });

    test('ensure that its using the PaginatorByCompanyTrait', function () {
        expect(in_array(PaginatorByCompanyTrait::class, class_uses_recursive($this->chargeableItemPriceService)))
            ->toBeTrue();
    });

    test('ensure that its using the CacheableFinderTrait', function () {
        expect(in_array(CacheableFinderTrait::class, class_uses_recursive($this->chargeableItemPriceService)))
            ->toBeTrue();
    });

    test('ensure that its using the DtoStorableTrait', function () {
        expect(in_array(DtoStorableTrait::class, class_uses_recursive($this->chargeableItemPriceService)))
            ->toBeTrue();
    });

    test('should implement ChargeableItemPriceUpdatableInterface interface', function () {
        expect($this->chargeableItemPriceService instanceof ChargeableItemPriceUpdatableInterface)
            ->toBeTrue();
    });

    test('should implement ChargeableItemPriceStorableInterface interface', function () {
        expect($this->chargeableItemPriceService instanceof ChargeableItemPriceStorableInterface)
            ->toBeTrue();
    });

    test('should implement ModelAware interface', function () {
        expect($this->chargeableItemPriceService instanceof ModelAware)
            ->toBeTrue();
    });

    test('should be using storeWithDtoAndAuthUserCompanyId to create a new chargeable item price', function () {
        $this->actingAs($this->userCompanyAdmin);

        $dto = new ChargeableItemPriceDto([]);

        $mockService = Mockery::mock(ChargeableItemPriceService::class)->makePartial();
        $expectedResult = new ChargeableItemPrice();

        $mockService->shouldReceive('storeWithDtoAndAuthUserCompanyId')
            ->once()
            ->andReturn($expectedResult);

        $result = $mockService->store($dto);

        expect($result)->toBe($expectedResult);
    });

    test('should be using updateWithDto to update a chargeable item price', function () {
        $this->actingAs($this->userCompanyAdmin);

        $id = 1;
        $dto = new ChargeableItemPriceDto([]);

        $mockService = Mockery::mock(ChargeableItemPriceService::class)->makePartial();
        $expectedResult = new ChargeableItemPrice();

        $mockService->shouldReceive('updateWithDto')
            ->once()
            ->andReturn($expectedResult);

        $result = $mockService->update($id, $dto);

        expect($result)->toBe($expectedResult);
    });

    test('should return the correct cache key', function () {
        $id = 1;
        $expectedCacheKey = config('cache_keys.chargeable_item_prices.by_id') . $id;

        $method = $this->invokeProtectedOrPrivateMethod(
            $this->chargeableItemPriceService,
            'getFindCacheKey',
            [$id]
        );

        expect($method)->toBe($expectedCacheKey);
    });
});
