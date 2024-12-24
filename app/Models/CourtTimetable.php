<?php

namespace App\Models;

use App\Enums\CourtTimetableStatus as EnumsCourtTimetableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtTimetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_id',
        'date',
        'start_time',
        'end_time',
        'status'
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getStatusAttribute($value)
    {
        return EnumsCourtTimetableStatus::from($value); 
    }

    public function setStatusAttribute(EnumsCourtTimetableStatus $status)
    {
        $this->attributes['status'] = $status->value;
    }
}
