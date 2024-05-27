<?php

namespace Database\Factories;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Models\ChargeableItem;
use App\Models\ChargeableItemPrice;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChargeableItemPriceFactory extends Factory
{
    protected $model = ChargeableItemPrice::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->randomNumber(),
            'currency' => $this->faker->randomElement(PaymentCurrencyEnum::values()),
            'chargeable_item_id' => ChargeableItem::factory(),
            'company_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
