<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'amount',
        'user_id',
        'company_id',
        'currency',
        'payment_generic_status_id',
        'payment_method_id',
        'expires_at',
        'payment_gateway_id',
    ];

    public $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function genericStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentGenericStatus::class, 'payment_generic_status_id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function chargeableItems(): belongsToMany
    {
        return $this->belongsToMany(ChargeableItem::class, 'chargeable_item_payment')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
