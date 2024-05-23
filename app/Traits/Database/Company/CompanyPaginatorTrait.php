<?php

namespace App\Traits\Database\Company;

use App\Traits\Database\ModelAccessorTrait;
use Illuminate\Pagination\LengthAwarePaginator;

trait CompanyPaginatorTrait
{
    use ModelAccessorTrait;

    public function getPaginatedByCompanyId(int $companyId, int $perPage): LengthAwarePaginator
    {
        return $this->getModel()->where('company_id', $companyId)->paginate($perPage);
    }
}
