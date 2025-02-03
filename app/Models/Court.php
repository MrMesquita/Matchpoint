<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Court extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'capacity',
        'arena_id'
    ];

    public function arena()
    {
        return $this->belongsTo(Arena::class);
    }

    public function timetables()
    {
        return $this->hasMany(CourtTimetable::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
