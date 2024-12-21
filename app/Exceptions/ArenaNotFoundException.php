<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ArenaNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Arena not found', Response::HTTP_NOT_FOUND);
    }
}
