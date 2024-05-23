<?php

namespace Database\Factories;

use App\Models\ChargeableItem;
use App\Models\ChargeableItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChargeableItemFactory extends Factory
{
    protected $model = ChargeableItem::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'currency' => $this->faker->currencyCode(),
            'price' => $this->faker->randomNumber(),
            'chargeable_item_category_id' => ChargeableItemCategory::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
