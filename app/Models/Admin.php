<?php

namespace App\Models;

class Admin extends User
{
    protected $table = 'users';

    protected $attributes = [
        'type' => 'admin'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin', function ($query) {
            $query->where('type', 'admin');
        });
    }
    
    public function arenas()
    {
        return $this->hasMany(Arena::class);
    }
}
