<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PaymentGatewayTransactionStatus */
class PaymentGatewayTransactionStatusesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            //
        ];
    }
}
