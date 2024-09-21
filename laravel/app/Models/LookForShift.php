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

    public function shiftContent()
    {
        return $this->belongsTo(ShiftContent::class);
    }

    public static function lookForShiftsLoaded(int $year, int $month, int $countOfDate){
        $lookForShifts = self::whereYear('date',$year)->whereMonth('date',$month)->get();
        $lookForShiftsLoaded=[];
        for ($i=1;$i<=$countOfDate;$i++){
            $lookForShiftsLoaded[$i]=[0,0,0,0];
        }
        for ($i=1;$i<=$countOfDate;$i++){
            $pointer=0;
            foreach ($lookForShifts as $lookForShift) {
                if ($lookForShift->date == $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($i, 2, "0", STR_PAD_LEFT)
                    && !in_array($lookForShift->shift_content_id, $lookForShiftsLoaded[$i])) {
                    $lookForShiftsLoaded[$i][$pointer++]=$lookForShift->shift_content_id;
                }
            }
        }
        return $lookForShiftsLoaded;
    }

    public static function lookForShiftIdsLoaded(int $year, int $month, int $countOfDate)
    {
        $lookForShifts = LookForShift::whereYear('date',$year)->whereMonth('date',$month)->orderBy("shift_content_id", "asc")->get();
        $lookForShiftIdsLoaded=[];
        for ($i=1;$i<=$countOfDate;$i++){
            $lookForShiftIdsLoaded[$i]=[0,0,0,0];
        }
        for ($i=1;$i<=$countOfDate;$i++){
            $pointer=0;
            foreach ($lookForShifts as $lookForShift) {
                if ($lookForShift->date == $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($i, 2, "0", STR_PAD_LEFT)
                    && !in_array($lookForShift->shift_content_id, $lookForShiftIdsLoaded[$i])) {
                    $lookForShiftIdsLoaded[$i][$pointer++]=$lookForShift->id;
                }
            }
        }
        return $lookForShiftIdsLoaded;
    }
}
