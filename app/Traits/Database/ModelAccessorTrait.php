<?php

namespace App\Traits\Database;

use Illuminate\Database\Eloquent\Model;

trait ModelAccessorTrait
{
    public function getModel(): Model
    {
        return $this->model;
    }
}
