<?php

namespace App\Traits\Database;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginatorByCompanyTrait
{
    use ModelAccessorTrait;

    /**
     * Get paginated records by company id.
     *
     * @param int $companyId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedByCompanyId(int $companyId, int $perPage): LengthAwarePaginator
    {
        return $this->getModel()->where('company_id', $companyId)->paginate($perPage);
    }
}
