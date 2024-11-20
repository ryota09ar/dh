<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCount extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "request_count",
        "year",
        "month",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
