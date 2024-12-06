<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'nome',
        'documento',
        'telefone',
        'email',
        'senha',
        'is_admin'
    ];

    protected $hidden = [
        'senha',
    ];
}
