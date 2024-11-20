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
    /**
     * @var mixed
     */

    public function requestShift()
    {
        return $this->hasMany(RequestShift::class);
    }

    public function requestCount()
    {
        return $this->hasMany(RequestCount::class);
    }

    public function decideShift()
    {
        return $this->hasMany(DecideShift::class);
    }
    public function return_name(){
        if (self::where('family_name', $this->family_name)->select('family_name')->groupBy('family_name')->havingRaw('COUNT(*) >= 2')->exists()) {
            return $this->family_name.mb_substr($this->first_name, 0, 1, "UTF-8");
        } else{
            return $this->family_name;
        }
    }
}
