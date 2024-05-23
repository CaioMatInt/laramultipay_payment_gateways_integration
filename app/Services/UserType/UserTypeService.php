<?php

namespace App\Services\UserType;

use App\Contracts\ModelAware;
use App\Models\UserType;
use App\Traits\Database\CacheableFinderByNameTrait;

class UserTypeService implements ModelAware
{
    use CacheableFinderByNameTrait;

    public function __construct(
        private readonly UserType $model,
    ) { }

    protected function getFindByNameCacheKey(string $name): string
    {
        return config('cache_keys.user_types.by_name') . $name;
    }
}
