<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentLog extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'event_type',
        'details',
    ];

    protected function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    protected function previousStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGenericStatus::class, 'previous_status_id');
    }

    protected function newStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGenericStatus::class, 'new_status_id');
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
