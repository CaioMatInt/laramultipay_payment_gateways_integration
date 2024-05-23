<?php

namespace App\Traits\Database;

use Illuminate\Support\Facades\Cache;

trait CacheableFinderTrait
{
    use ModelAccessorTrait;

    public function findCached(int $id)
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever($this->getFindCacheKey($id), function () use ($id) {
            return $this->getModel()->findOrFail($id);
        });
    }

    abstract protected function getFindCacheKey(int $id): string;
}
