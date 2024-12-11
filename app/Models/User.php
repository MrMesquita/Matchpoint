<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'password'
    ];

    protected $attributes = [
        'type' => 'customer'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function isCustomer()
    {
        return $this->type === 'customer';
    }
}
