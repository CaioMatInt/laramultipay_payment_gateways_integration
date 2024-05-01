<?php

namespace App\Services\Company;

use App\Models\Company;

class CompanyService
{
    public function __construct(
        private readonly Company $model
    ) { }
}
