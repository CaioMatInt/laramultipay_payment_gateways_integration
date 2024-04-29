<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PaymentLog */
class PaymentLogsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'event_type' => $this->event_type,
            'details' => $this->details,

            'payment' => new PaymentResource($this->whenLoaded('payment')),
        ];
    }
}
