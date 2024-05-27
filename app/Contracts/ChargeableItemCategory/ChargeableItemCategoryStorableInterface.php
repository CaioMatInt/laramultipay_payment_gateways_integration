<?php

namespace App\Contracts\ChargeableItemCategory;
use App\DTOs\ChargeableItemCategory\ChargeableItemCategoryDto;
use App\Models\ChargeableItemCategory;

interface ChargeableItemCategoryStorableInterface
{
    function store(ChargeableItemCategoryDto $dto): ChargeableItemCategory;
}
