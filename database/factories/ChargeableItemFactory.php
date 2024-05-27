<?php

namespace Database\Factories;

use App\Models\ChargeableItem;
use App\Models\ChargeableItemCategory;
use App\Models\Company;
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
            'chargeable_item_category_id' => ChargeableItemCategory::factory(),
            'company_id' => Company::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
