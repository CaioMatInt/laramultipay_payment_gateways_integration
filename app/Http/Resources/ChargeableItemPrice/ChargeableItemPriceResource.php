<?php

namespace App\Http\Resources\ChargeableItemPrice;

use App\Http\Resources\ChargeableItem\ChargeableItemResource;
use App\Services\ChargeableItem\ChargeableItemService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ChargeableItemCategory */
class ChargeableItemPriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $chargeableItemService = app(ChargeableItemService::class);

        $chargeableItem = $chargeableItemService->findCached($this->chargeable_item_id);

        return [
            'id' => $this->id,
            'price' => $this->price,
            'currency' => $this->currency,
            'chargeable_item' => new ChargeableItemResource($chargeableItem),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
