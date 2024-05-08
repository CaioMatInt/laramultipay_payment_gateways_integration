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
        'currency',
        'price',
        'chargeable_item_category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ChargeableItemCategory::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'chargeable_item_payment')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
