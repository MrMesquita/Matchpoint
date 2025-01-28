<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Exceptions\ReservationNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'court_id',
        'court_timetable_id',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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

    public function setStatusAttribute($status)
    {
        if (is_string($status)) {
            $status = ReservationStatus::from($status);
        }

        $this->attributes['status'] = $status->value;
    }

    public function getReservationsByAcess($type, $userId = null)
    {
        $query = $this->with(['customer', 'court', 'courtTimetable']);

        if ($type === 'admin' && $userId !== null) {
            return $query->whereHas('court.arena', function ($q) use ($userId) {
                $q->where('admin_id', $userId);
            })->get();
        } else if ($type === 'customer' && $userId !== null) {
            return $query->where('customer_id', $userId)->get();
        }

        return $query->get();
    }

    public function getReservationById($id, $user)
    {
        $query = $this->with(['customer', 'court', 'courtTimetable']);
    
        if ($user->type === 'admin') {
            $query->whereHas('court.arena', function ($q) use ($user) {
                $q->where('admin_id', $user->id);
            });
        } elseif ($user->type === 'customer') {
            $query->where('customer_id', $user->id);
        }
    
        $reservation = $query->find($id);
    
        return $reservation;
    }    

    public function existsConflictingReservation($courtId, $courtTimetable) 
    {
        $conflictingReservation = $this->where('court_id', $courtId)
            ->where('status', ReservationStatus::CONFIRMED)
            ->whereHas('courtTimetable', function ($query) use ($courtTimetable) {
                $query->where('day_of_week', $courtTimetable->day_of_week) 
                    ->where('start_time', '<', $courtTimetable->end_time)
                    ->where('end_time', '>', $courtTimetable->start_time);
            })
            ->exists();
    
        return $conflictingReservation;
    }  
    
    public function getPendingReservationsForSchedule($courtId, $courtTimetableId) 
    {
        $reservations = $this->where('status', ReservationStatus::PENDING)
            ->where('court_id', $courtId)
            ->where('court_timetable_id', $courtTimetableId)
            ->get();   
            
        return $reservations;
    }
}
