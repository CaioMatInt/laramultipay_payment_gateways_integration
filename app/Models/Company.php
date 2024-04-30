<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
    ];

    protected function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    protected function paymentGatewayKeys(): HasMany
    {
        return $this->hasMany(PaymentGatewayKey::class);
    }

    protected function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
