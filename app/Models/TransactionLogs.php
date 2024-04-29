<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionLogs extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'event_type',
        'details',
    ];

    protected function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    protected function previousStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGatewayTransactionStatus::class, 'previous_status_id');
    }

    protected function newStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGatewayTransactionStatus::class, 'new_status_id');
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
