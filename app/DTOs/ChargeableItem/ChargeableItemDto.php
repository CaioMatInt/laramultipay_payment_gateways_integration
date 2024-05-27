<?php

namespace App\DTOs\ChargeableItem;

use App\DTOs\BaseDto;

class ChargeableItemDto extends BaseDto
{
    public string $name;
    public string $description;
    public int $price;
    public string $currency;
    public int $chargeable_item_category_id;
}
