<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CourtNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Court not found', Response::HTTP_NOT_FOUND);
    }
}
