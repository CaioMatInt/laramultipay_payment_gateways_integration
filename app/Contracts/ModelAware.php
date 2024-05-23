<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ModelAware
{
    /**
     * Get the model instance.
     *
     * @return Model
     */
    function getModel(): Model;
}
