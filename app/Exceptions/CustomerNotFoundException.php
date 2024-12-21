<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CustomerNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Customer not found', Response::HTTP_NOT_FOUND);
    }
}
