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
        $lookForShiftsLoaded=[];
        for ($i=1;$i<=$countOfDate;$i++){
            $lookForShiftsLoaded[$i]=[0,0,0,0];
        }
        for ($i=1;$i<=$countOfDate;$i++){
            $pointer=0;
            $lookForShifts = self::whereYear('date',$year)->whereMonth('date',$month)->whereDay("date", $i)->orderBy("shift_content_id", "asc")->get();
            foreach ($lookForShifts as $lookForShift) {
                if (!in_array($lookForShift->shift_content_id, $lookForShiftsLoaded[$i])) {
                    $lookForShiftsLoaded[$i][$pointer++]=$lookForShift->shift_content_id;
                }
            }
        }
        return $lookForShiftsLoaded;
    }

    public static function lookForShiftIdsLoaded(int $year, int $month, int $countOfDate)
    {
        $lookForShiftIdsLoaded=[];
        for ($i=1;$i<=$countOfDate;$i++){
            $lookForShiftIdsLoaded[$i]=[0,0,0,0];
        }
        for ($i=1;$i<=$countOfDate;$i++){
            $pointer=0;
            $lookForShifts = self::whereYear('date',$year)->whereMonth('date',$month)->whereDay("date", $i)->orderBy("shift_content_id", "asc")->get();
            foreach ($lookForShifts as $lookForShift) {
                if (!in_array($lookForShift->id, $lookForShiftIdsLoaded[$i])) {
                    $lookForShiftIdsLoaded[$i][$pointer++]=$lookForShift->id;
                }
            }
        }
        return $lookForShiftIdsLoaded;
    }
}
