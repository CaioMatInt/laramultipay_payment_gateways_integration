<?php

namespace App\Exceptions\Authentication;

use Exception;

class ProviderMismatchException extends Exception
{
    public function __construct($userEmail, $providerName)
    {
        $message = "You tried signing in as {$userEmail} via {$providerName}, which is not the authentication method
         you used during sign up. Try again using the authentication method you used during sign up.";
        parent::__construct($message, 400);
    }
}
