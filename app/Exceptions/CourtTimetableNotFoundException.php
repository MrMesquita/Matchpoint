<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CourtTimetableNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Court timetable not found', Response::HTTP_NOT_FOUND);
    }
}
