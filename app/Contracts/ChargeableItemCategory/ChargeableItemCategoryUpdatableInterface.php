<?php

namespace App\Contracts\ChargeableItemCategory;
use App\DTOs\ChargeableItemCategory\ChargeableItemCategoryDto;
use App\Models\ChargeableItemCategory;

interface ChargeableItemCategoryUpdatableInterface
{
    function update(int $id, ChargeableItemCategoryDto $dto): ChargeableItemCategory;
}
