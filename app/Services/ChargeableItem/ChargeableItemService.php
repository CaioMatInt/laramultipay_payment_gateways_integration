<?php

namespace App\Services\ChargeableItem;

use App\Contracts\ChargeableItem\ChargeableItemUpdatableInterface;
use App\Contracts\ModelAware;
use App\DTOs\ChargeableItem\ChargeableItemDto;
use App\Models\ChargeableItem;
use App\Traits\Database\CacheableFinderTrait;
use App\Traits\Database\DestroyableTrait;
use App\Traits\Database\DtoStorableTrait;
use App\Traits\Database\DtoUpdatableTrait;
use App\Traits\Database\PaginatorByCompanyTrait;

class ChargeableItemService implements ModelAware, ChargeableItemUpdatableInterface
{
    use PaginatorByCompanyTrait, DtoStorableTrait, CacheableFinderTrait, DtoUpdatableTrait, DestroyableTrait;

    public function __construct(
        private readonly ChargeableItem $model,
    ) { }

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.chargeable_items.by_id') . $id;
    }

    public function store(ChargeableItemDto $dto): ChargeableItem
    {
        return $this->storeWithDtoAndAuthUserCompanyId($dto);
    }

    public function update(int $id, ChargeableItemDto $dto): ChargeableItem
    {
        return $this->updateWithDto($id, $dto);
    }
}
