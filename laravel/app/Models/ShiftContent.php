<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftContent extends Model
{
    use HasFactory;

    protected $fillable = [
        "place",
        "time"
    ];

    public function shift(){
        return $this->hasMany(Shift::class);
    }

    public function lookForShift(){
        return $this->hasMany(LookForShift::class);
    }
}
