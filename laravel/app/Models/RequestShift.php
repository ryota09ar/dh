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

    public function lookForShift(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
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

    public static function requestShiftsId($user): array
    {
        $requestShiftsId = [];
        $requestShifts=$user->requestShift;
        foreach ($requestShifts as $requestShift) {
            $requestShiftsId[] = $requestShift->look_for_shift_id;
        }
        return $requestShiftsId;
    }

    public static function existsYearMonth(int $year, int $month): bool
    {
        return self::whereYear("date", $year)->whereMonth("date", $month)->exists();
    }

    public static function requestedUsers(int $year, int $month): array
    {
        $requestUserIds = self::whereYear("date", $year)->whereMonth("date", $month)->select("user_id")->distinct()->get();
        $requestedUsers = [];
        foreach ($requestUserIds as $requestedUserId){
            $requestedUsers[] = User::find($requestedUserId);
        }
        return $requestedUsers;
    }
}
