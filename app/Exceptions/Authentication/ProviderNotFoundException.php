<?php

namespace App\Exceptions\Authentication;

use Exception;

class ProviderNotFoundException extends Exception
{
    protected $message = 'Provider not found';
    protected $code = 404;
}
