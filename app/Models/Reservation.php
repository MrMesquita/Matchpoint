<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'court_id',
        'court_timetable_id',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_user');
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
