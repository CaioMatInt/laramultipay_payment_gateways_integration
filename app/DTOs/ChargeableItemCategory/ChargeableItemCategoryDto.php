<?php

namespace App\DTOs\ChargeableItemCategory;

use App\DTOs\BaseDto;

class ChargeableItemCategoryDto extends BaseDto
{
    public string $name;

    /**
     * @param array{ name: string } $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }
}
