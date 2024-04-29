<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Payment */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,

            'company' => new CompanyResource($this->whenLoaded('company')),
            'paymentMethod' => new PaymentMethodResource($this->whenLoaded('paymentMethod')),
        ];
    }
}
