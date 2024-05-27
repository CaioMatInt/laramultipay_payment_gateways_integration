<?php

namespace App\Contracts\ChargeableItem;

use App\DTOs\ChargeableItem\ChargeableItemDto;
use App\Models\ChargeableItem;

interface ChargeableItemUpdatableInterface
{
    function update(int $id, ChargeableItemDto $dto): ChargeableItem;
}
