<?php

namespace App\Traits\Database;

use Illuminate\Support\Facades\Cache;

trait CacheableFinderByCompanyTrait
{
    use ModelAccessorTrait;

    public function findCachedByUserCompanyId(int $id)
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever($this->getFindWithCompanyCacheKey($id), function () use ($id) {
            return $this->getModel()->where('company_id', auth()->user()->company_id)->findOrFail($id);
        });
    }

    abstract protected function getFindWithCompanyCacheKey(int $id): string;
}
