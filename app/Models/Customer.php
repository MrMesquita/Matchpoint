<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('customer', function ($query) {
            $query->where('type', 'customer');
        });
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'customer_id');
    }
}
