<?php

namespace App\Models;

use App\Constants\UserTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        return $this->type === UserTypes::ADMIN;
    }

    public function isCustomer()
    {
        return $this->type === UserTypes::CUSTOMER;
    }

    public function isSuperAdmin()
    {
        return $this->type === UserTypes::SUPERADMIN;
    }
}
