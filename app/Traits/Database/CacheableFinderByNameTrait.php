<?php

namespace App\Traits\Database;

use Illuminate\Support\Facades\Cache;

trait CacheableFinderByNameTrait
{
    use ModelAccessorTrait;

    public function findCachedByName(string $name)
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever($this->getFindByNameCacheKey($name), function () use ($name) {
            return $this->getModel()->where('name', $name)->firstOrFail();
        });
    }

    abstract protected function getFindByNameCacheKey(string $name): string;
}
