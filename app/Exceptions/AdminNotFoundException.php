<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AdminNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Administrator not found', Response::HTTP_NOT_FOUND);
    }
}
