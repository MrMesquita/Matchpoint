<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'document',
        'phone',
        'email',
        'password',
        'is_admin'
    ];

    protected $hidden = [
        'password',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_customer');
    }
}
