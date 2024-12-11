<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arena extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street',
        'number',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'admin_id'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
