<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargeableItem extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'chargeable_item_category_id',
        'company_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ChargeableItemCategory::class, 'chargeable_item_category_id', 'id');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'chargeable_item_payment')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function prices(): BelongsToMany
    {
        return $this->belongsToMany(ChargeableItemPrice::class, 'chargeable_item_prices');
    }
}
