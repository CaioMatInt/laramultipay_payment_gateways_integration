<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGatewayKey extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'payment_gateway_id',
        'company_id',
        'key'
    ];

    protected function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    protected function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
