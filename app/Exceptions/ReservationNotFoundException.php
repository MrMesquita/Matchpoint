<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ReservationNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Reservation not found', Response::HTTP_NOT_FOUND);
    }
}
