<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case CANCQELED = 'canceled';
    case CONFIRMED = 'confirmed';
    case PENDING = 'pending';
}
