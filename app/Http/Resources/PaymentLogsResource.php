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
            //'payment' => new PaymentResource($this->whenLoaded('payment')),
        ];
    }
}
