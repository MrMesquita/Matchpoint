<?php

namespace App\Enums;

enum CourtTimetableStatus: string
{
    case AVAILABLE = 'available';
    case BUSY = 'busy';
}