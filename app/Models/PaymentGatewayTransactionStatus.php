<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction;

class PaymentGatewayTransactionStatus extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
    ];

    protected function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    protected function genericStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGenericStatus::class);
    }
}
