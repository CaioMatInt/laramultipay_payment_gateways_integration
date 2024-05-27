<?php

namespace App\Services\ChargeableItemCategory;

use App\Contracts\ChargeableItemCategory\ChargeableItemCategoryUpdatableInterface;
use App\Contracts\ModelAware;
use App\DTOs\ChargeableItemCategory\ChargeableItemCategoryDto;
use App\Models\ChargeableItemCategory;
use App\Traits\Database\CacheableFinderTrait;
use App\Traits\Database\DestroyableTrait;
use App\Traits\Database\DtoStorableTrait;
use App\Traits\Database\DtoUpdatableTrait;
use App\Traits\Database\PaginatorByCompanyTrait;

class ChargeableItemCategoryService implements ModelAware, ChargeableItemCategoryUpdatableInterface
{
    use CacheableFinderTrait, PaginatorByCompanyTrait, DestroyableTrait, DtoUpdatableTrait, DtoStorableTrait;

    public function __construct(
        private readonly ChargeableItemCategory $model,
    ) { }

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.chargeable_item_categories.by_id') . $id;
    }

    public function store(ChargeableItemCategoryDto $dto): ChargeableItemCategory
    {
        return $this->storeWithDtoAndAuthUserCompanyId($dto);
    }

    public function update(int $id, ChargeableItemCategoryDto $dto): ChargeableItemCategory
    {
        return $this->updateWithDto($id, $dto);
    }
}
