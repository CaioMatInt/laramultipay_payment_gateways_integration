<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeGetIdByName($query, string $name): self
    {
        return $query->select('id')->where('name', $name);
    }
}
