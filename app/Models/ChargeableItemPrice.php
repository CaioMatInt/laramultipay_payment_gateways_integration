<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChargeableItemPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'currency',
        'chargeable_item_id',
        'company_id',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(ChargeableItem::class, 'chargeable_item_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
