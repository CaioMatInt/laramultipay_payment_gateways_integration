<?php

namespace App\Http\Resources\ChargeableItem;

use App\Http\Resources\ChargeableItemCategory\ChargeableItemCategoryResource;
use App\Services\ChargeableItemCategory\ChargeableItemCategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ChargeableItem */
class ChargeableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $chargeableItemCategoryService = app(ChargeableItemCategoryService::class);
        $chargeableItemCategory = $chargeableItemCategoryService->findCached($this->chargeable_item_category_id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => new ChargeableItemCategoryResource($chargeableItemCategory),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
