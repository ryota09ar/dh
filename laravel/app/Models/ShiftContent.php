<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftContent extends Model
{
    use HasFactory;

    protected $fillable = [
        "shift_id",
        "shift_place",
        "shift_time"
    ];
}
