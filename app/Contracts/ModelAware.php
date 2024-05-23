<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ModelAware
{
    function getModel(): Model;
}
