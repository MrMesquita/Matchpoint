<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_customer',
        'id_court',
        'id_court_timetable',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    
    public function court()
    {
        return $this->belongsTo(Court::class);
    }
    
    public function courtTimetable()
    {
        return $this->belongsTo(CourtTimetable::class);
    }

    public function payout()
    {
        return $this->hasOne(Payout::class);
    }

    public function getStatusAttribute($value)
    {
        return ReservationStatus::from($value); 
    }

    public function setStatusAttribute(ReservationStatus $status)
    {
        $this->attributes['status'] = $status->value;
    }
}
