<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable=[
        "id",
        'family_name',
        'first_name',
        'dh_staff',
        'email',
        "password"
    ];

    public function requestShift()
    {
        return $this->hasMany(RequestShift::class);
    }

    public function requestCount()
    {
        return $this->hasMany(RequestCount::class);
    }
}
