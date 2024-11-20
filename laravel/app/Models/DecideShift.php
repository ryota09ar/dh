<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecideShift extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "date",
        "place",
        "time",
        "makeDhByOneself"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function bubble(&$list, $pointer): void
    {
        if ($pointer == 0){
            return;
        } else if($list[$pointer]!=0){
            $temp=$list[$pointer];
            $list[$pointer]=$list[$pointer-1];
            $list[$pointer-1]=$temp;
            self::bubble($list, $pointer-1);
        }
    }

    public static function lookForSortByDecided(&$lookForShiftIdsLoaded, int $countOfDate)
    {
        for ($i=1;$i<=$countOfDate;$i++){
            for ($j=0;$j<4;$j++){
                //実施する人がいる場所のみを表示する
                if ($lookForShiftIdsLoaded[$i][$j]!=0
                    && !DecideShift::where("place", LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->place)
                        ->where("time", LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->time)
                        ->where("date", LookForShift::find($lookForShiftIdsLoaded[$i][$j])->date)->exists()) {
                    $lookForShiftIdsLoaded[$i][$j]=0;
                }
            }
            for ($j=1;$j<4;$j++){
                self::bubble($lookForShiftIdsLoaded[$i], $j);
            }
        }
    }
}
