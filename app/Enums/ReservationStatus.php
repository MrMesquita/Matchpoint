<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case CANCELED = 'canceled';
    case CONFIRMED = 'confirmed';
    case PENDING = 'pending';
}
