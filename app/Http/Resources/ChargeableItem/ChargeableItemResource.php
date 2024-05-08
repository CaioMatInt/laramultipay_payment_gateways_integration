<?php

namespace App\Http\Resources\ChargeableItem;

use App\Http\Resources\ChargeableItemCategory\ChargeableItemCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ChargeableItem */
class ChargeableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'currency' => $this->currency,
            'price' => $this->price,
            'chargeableItemCategory' => new ChargeableItemCategoryResource($this->whenLoaded('chargeableItemCategory')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
