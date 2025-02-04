<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ReservationCanceledException extends Exception
{
    public function __construct()
    {
        parent::__construct('The reservation has been canceled. Canceled reservations cannot be confirmed', Response::HTTP_CONFLICT);
    }
}
