<?php

namespace App\Services\ChargeableItemPrice;

use App\Contracts\ChargeableItemPrice\ChargeableItemPriceStorableInterface;
use App\Contracts\ChargeableItemPrice\ChargeableItemPriceUpdatableInterface;
use App\Contracts\ModelAware;
use App\DTOs\ChargeableItemPrice\ChargeableItemPriceDto;
use App\Models\ChargeableItemPrice;
use App\Traits\Database\CacheableFinderByCompanyTrait;
use App\Traits\Database\CacheableFinderTrait;
use App\Traits\Database\DestroyableTrait;
use App\Traits\Database\DtoStorableTrait;
use App\Traits\Database\DtoUpdatableTrait;
use App\Traits\Database\PaginatorByCompanyTrait;
use Illuminate\Support\Collection;

class ChargeableItemPriceService implements ModelAware, ChargeableItemPriceUpdatableInterface, ChargeableItemPriceStorableInterface
{
    use CacheableFinderTrait, PaginatorByCompanyTrait, DestroyableTrait, DtoUpdatableTrait, DtoStorableTrait;

    public function __construct(
        private readonly ChargeableItemPrice $model,
    ) { }

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.chargeable_item_prices.by_id') . $id;
    }

    public function getByChargeableItemId(int $chargeableItemId): Collection
    {
        return $this->model->where('chargeable_item_id', $chargeableItemId)->get();
    }

    //@@TODO: Add Caching
    public function findByIdAndChargeableItemId(int $id, int $chargeableItemId): ChargeableItemPrice
    {
        return $this->model->where('id', $id)->where('chargeable_item_id', $chargeableItemId)->firstOrFail();
    }

    public function store(ChargeableItemPriceDto $dto): ChargeableItemPrice
    {
        return $this->storeWithDtoAndAuthUserCompanyId($dto);
    }

    public function update(int $id, ChargeableItemPriceDto $dto): ChargeableItemPrice
    {
        return $this->updateWithDto($id, $dto);
    }
}
