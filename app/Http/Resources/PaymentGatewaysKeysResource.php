<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PaymentGatewayKey */
class PaymentGatewaysKeysResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,

            'paymentGateway' => new PaymentGatewayResource($this->whenLoaded('paymentGateway')),
            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
