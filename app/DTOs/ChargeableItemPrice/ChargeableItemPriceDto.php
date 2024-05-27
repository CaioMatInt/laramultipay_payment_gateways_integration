<?php

namespace App\DTOs\ChargeableItemPrice;

use App\DTOs\BaseDto;

class ChargeableItemPriceDto extends BaseDto
{
    public int $price;
    public string $currency;
    public int $chargeable_item_id;
}
