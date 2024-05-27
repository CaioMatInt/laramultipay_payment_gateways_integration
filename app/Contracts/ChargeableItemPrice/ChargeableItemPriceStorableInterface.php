<?php

namespace App\Contracts\ChargeableItemPrice;

use App\DTOs\ChargeableItemPrice\ChargeableItemPriceDto;
use App\Models\ChargeableItemPrice;

interface ChargeableItemPriceStorableInterface
{
    function store(ChargeableItemPriceDto $dto): ChargeableItemPrice;
}
