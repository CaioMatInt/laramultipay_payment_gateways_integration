<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGenericStatus extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
    ];

    public function scopeWhereName($query, string $name)
    {
        return $query->where('name', $name);
    }

    protected function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function gatewayTransactionStatuses(): HasMany
    {
        return $this->hasMany(PaymentGatewayTransactionStatus::class);
    }
}
