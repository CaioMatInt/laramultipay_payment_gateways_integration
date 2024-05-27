<?php

namespace App\Contracts\ChargeableItemPrice;

use App\DTOs\ChargeableItemPrice\ChargeableItemPriceDto;
use App\Models\ChargeableItemPrice;

interface ChargeableItemPriceUpdatableInterface
{
    function update(int $id, ChargeableItemPriceDto $dto): ChargeableItemPrice;
}
