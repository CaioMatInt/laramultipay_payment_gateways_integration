<?php

namespace App\Contracts\ChargeableItem;

use App\DTOs\ChargeableItem\ChargeableItemDto;
use App\Models\ChargeableItem;

interface ChargeableItemStorableInterface
{
    function store(ChargeableItemDto $dto): ChargeableItem;
}
