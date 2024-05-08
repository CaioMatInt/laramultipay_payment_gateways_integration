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
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.user_types.by_name') . $name, function () use ($name) {
            return $this->model->where('name', $name)->firstOrFail();
        });
    }
}
