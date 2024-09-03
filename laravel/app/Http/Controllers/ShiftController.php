<?php

namespace App\Http\Controllers;

use App\Models\LookForShift;
use App\Models\Shift;
use App\Models\ShiftContent;
use App\Models\RequestShift;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function countOfDate($year, $month): int
    {
        $date = new DateTime("$year-$month-01");
        return (int)$date->format("t");
    }

    public function daysOfWeek($year, $month, $countOfDate): array
    {
        $daysOfWeek = [];
        for ($i=1;$i<=$countOfDate;$i++){
            $date_a = new DateTime("$year-$month-".str_pad($i, 2, "0", STR_PAD_LEFT));
            switch ($date_a->format("D")) {
                case "Mon":
                    $daysOfWeek[$i]="月";
                    break;
                case "Tue":
                    $daysOfWeek[$i]="火";
                    break;
                case "Wed":
                    $daysOfWeek[$i]="水";
                    break;
                case "Thu":
                    $daysOfWeek[$i]="木";
                    break;
                case "Fri":
                    $daysOfWeek[$i]="金";
                    break;
                case "Sat":
                    $daysOfWeek[$i]="土";
                    break;
                case "Sun":
                    $daysOfWeek[$i]="日";
                    break;
            }
        }
        return $daysOfWeek;
    }

    public function requestYearMonth(){
        return view('shift.requestYearMonth');
    }

    public function requestCreate(Request $request)
    {
        $user = Auth::user();
        $requestShiftsId = [];
        $requestShifts=$user->requestShift;
        foreach ($requestShifts as $requestShift) {
            $requestShiftsId[]=$requestShift->look_for_shift_id;
        }
        $year=$request['year'];
        $month=$request['month'];
        $countOfDate=$this->countOfDate($year, $month);
        $daysOfWeek=$this->daysOfWeek($year, $month, $countOfDate);
        $lookForShifts=LookForShift::whereYear('date',$year)->whereMonth('date',$month)->get();
        $lookForShiftsLoaded=[];
        for ($i=1;$i<=$countOfDate;$i++){
            $lookForShiftsLoaded[$i]=[];
        }
        foreach ($lookForShifts as $lookForShift){
            $date = new Carbon($lookForShift->date);
            $day = $date->day;
            $lookForShiftsLoaded[$day][]=$lookForShift;
        }

        return view('shift.requestCreate', compact( "user","year", "month", "lookForShiftsLoaded", "countOfDate", "daysOfWeek", "requestShiftsId"));
    }

    public function requestStore(Request $request){
        $selectedShifts = $request->input('lookForShiftIds', []);
        RequestShift::where("user_id", Auth::id())->whereYear("date", $request["year"])->whereMonth("date", $request["month"])->delete();

        foreach ($selectedShifts as $lookForShiftId){
            RequestShift::create([
                "date"=>LookForShift::find($lookForShiftId)->date,
                "user_id"=>Auth::id(),
                "look_for_shift_id"=>$lookForShiftId,
            ]);
        }

        return redirect()->route("user.home");
    }
}
