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

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function paymentGatewayKeys(): HasMany
    {
        return $this->hasMany(PaymentGatewayKey::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function chargeableItemCategories(): HasMany
    {
        return $this->hasMany(ChargeableItemCategory::class);
    }

    public function chargeableItems(): HasMany
    {
        return $this->hasMany(ChargeableItem::class);
    }

    public function chargeableItemPrices(): HasMany
    {
        return $this->hasMany(ChargeableItemPrice::class);
    }
}
