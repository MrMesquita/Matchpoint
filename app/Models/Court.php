<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

class Court extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

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

    public function getAdminOwner()
    {
        return $this->arena->admin ?? null;
    }
}
