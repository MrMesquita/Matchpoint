<?php

namespace App\Models;

class Customer extends User
{
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
