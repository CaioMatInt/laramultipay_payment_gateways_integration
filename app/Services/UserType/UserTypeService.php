<?php

namespace App\Services\UserType;

use App\Models\Payment;
use App\Models\UserType;

class UserTypeService
{
    public function __construct(
        private readonly UserType $model,

    ) { }

    public function findByName(string $name): UserType
    {
        //@@TODO add caching
        return $this->model->where('name', $name)->first();
    }
}
