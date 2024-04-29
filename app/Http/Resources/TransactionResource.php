<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Transaction */
class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'gateway_status' => $this->gateway_status,
            'response_code' => $this->response_code,
            'date' => $this->date,

            'payment' => new PaymentResource($this->whenLoaded('payment')),
        ];
    }
}
