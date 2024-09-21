<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestShift extends Model
{
    use HasFactory;

    protected $fillable = [
        "date",
        'look_for_shift_id',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function lookForShift(){
        return $this->belongsTo(LookForShift::class);
    }

    public static function requestShiftsLoaded(int $year, int $month, int $countOfDate)
    {
        $requestShiftsLoaded=[];
        for ($i=1;$i<=$countOfDate;$i++){
            $requestShiftsLoaded[$i]=RequestShift::whereYear("date", $year)->whereMonth('date', $month)->whereDay('date', $i)->orderBy("user_id", "asc")->get();
        }
        return $requestShiftsLoaded;
    }
}
