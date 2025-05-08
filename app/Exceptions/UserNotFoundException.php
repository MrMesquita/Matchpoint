<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('User not found', Response::HTTP_NOT_FOUND);
    }
}
