<?php

namespace App\Services\Company;

use App\Models\Company;

class CompanyService
{
    public function __construct(
        private readonly Company $model
    ) { }

    public function create(array $data): Company
    {
        return $this->model->create($data);
    }
}
