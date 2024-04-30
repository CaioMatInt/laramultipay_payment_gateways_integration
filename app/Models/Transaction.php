<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'gateway_transaction_id',
        'gateway_status',
        'response_code',
        'date',
        'payment_id',
    ];

    protected function casts()
    {
        return [
            'date' => 'date',
        ];
    }

    protected function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    protected function gatewayTransactionStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGatewayTransactionStatus::class);
    }

    protected function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
