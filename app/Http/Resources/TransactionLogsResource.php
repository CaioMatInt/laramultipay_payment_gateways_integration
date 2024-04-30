<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TransactionLogs */
class TransactionLogsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            //
        ];
    }
}
