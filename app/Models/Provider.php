<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    const GOOGLE = 'google';
    const FACEBOOK = 'facebook';
    const GITHUB = 'github';

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeSelectIdByName($query, $name)
    {
        return $query->select('id')->where('name', $name);
    }
}
