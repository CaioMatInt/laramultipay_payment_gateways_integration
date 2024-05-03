<?php

namespace App\Services\UserType;

use App\Models\UserType;
use Illuminate\Support\Facades\Cache;

class UserTypeService
{
    public function __construct(
        private readonly UserType $model,
    ) { }

    public function findCachedByName(string $name): UserType
    {
        return Cache::rememberForever('user_type_' . $name, function () use ($name) {
            return $this->model->where('name', $name)->firstOrFail();
        });
    }
}
