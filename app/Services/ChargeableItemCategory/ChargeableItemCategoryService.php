<?php

namespace App\Services\ChargeableItemCategory;

use App\Contracts\ModelAware;
use App\DTOs\ChargeableItemCategory\ChargeableItemCategoryDto;
use App\Models\ChargeableItemCategory;
use App\Traits\Database\CacheableFinderTrait;
use App\Traits\Database\Company\CompanyPaginatorTrait;

class ChargeableItemCategoryService implements ModelAware
{
    use CacheableFinderTrait, CompanyPaginatorTrait;

    public function __construct(
        private readonly ChargeableItemCategory $model,
    ) { }

    protected function getFindCacheKey(int $id): string
    {
        return config('cache_keys.chargeable_item_categories.by_id') . $id;
    }

    //@@TODO: Check if I can move this to a Trait, call $dto->toArray()
    public function update(int $id, ChargeableItemCategoryDto $dto): ChargeableItemCategory
    {
        $chargeableItemCategory = $this->model->find($id);

        $chargeableItemCategory->update([
            'name' => $dto->name
        ]);
        return $chargeableItemCategory;
    }

    //@@TODO: Check if I can move this to a Trait, call $dto->toArray()
    public function store(ChargeableItemCategoryDto $dto): ChargeableItemCategory
    {
        return $this->model->create([
            'name' => $dto->name,
            'company_id' => auth()->user()->company_id,
        ]);
    }

    //@@TODO: Check if I can move this to a Trait
    public function destroy(int $id): void
    {
        $this->model->destroy($id);
    }
}
