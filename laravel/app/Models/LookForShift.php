<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookForShift extends Model
{
    use HasFactory;

    protected $fillable = [
        "date",
        "shift_content_id"
    ];

    public function shiftContent(){
        return $this->belongsTo(ShiftContent::class);
    }
}
