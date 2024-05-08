<?php

namespace Database\Factories;

use App\Models\ChargeableItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChargeableItemCategoryFactory extends Factory
{
    protected $model = ChargeableItemCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
