<?php

namespace App\Http\Controllers;

use App\Models\LookForShift;
use App\Models\ShiftContent;
use Illuminate\Http\Request;

class ShiftAdminController extends Controller
{
    public function show(){
        return view('admin.menu');
    }

    public function placeIndex(){
        $shift_contents = ShiftContent::all();
        return view('admin.placeIndex', compact('shift_contents'));
    }

    public function placeCreate(){
        return view('admin.placeCreate');
    }

    public function placeStore(Request $request){
        $validated=$request->validate([
            "place"=>"required",
            "hour"=>"required",
            "minute"=>"required",
        ]);

        ShiftContent::create([
            "place"=>$validated["place"],
            "time"=>str_pad($validated["hour"], 2, "0", STR_PAD_LEFT).":".str_pad($validated["minute"], 2, "0", STR_PAD_LEFT),
        ]);

        return redirect()->route('shiftPlace.index');
    }

    public function placeEdit(Request $request){
        $shift_content=ShiftContent::find($request['id']);
        return view('admin.placeEdit', compact('shift_content'));
    }

    public function placeUpdate(Request $request){
        $shift_content=ShiftContent::find($request['id']);

        $validated=$request->validate([
            "place"=>"required",
            "hour"=>"required",
            "minute"=>"required",
        ]);

        $shift_content->place=$validated["place"];
        $shift_content->time=str_pad($validated["hour"], 2, "0", STR_PAD_LEFT).":".str_pad($validated["minute"], 2, "0", STR_PAD_LEFT);
        $shift_content->save();

        return redirect()->route('shiftPlace.index');
    }

    public function lookForYearMonth(){
        return view('admin.lookForYearMonth');
    }

    public function lookForCreate(Request $request){
        $year=$request['year'];
        $month=$request['month'];
        $lookForShifts=LookForShift::whereYear('date',$year)->whereMonth('date',$month)->get();
        $shift_contents = ShiftContent::all();
        $lookForShiftsLoaded=[];
        for ($i=1;$i<=31;$i++){
            $lookForShiftsLoaded[]=[0,0,0,0];
        }
        for ($i=1;$i<=31;$i++){
            $pointer=0;
            foreach ($lookForShifts as $lookForShift) {
                if ($lookForShift->date == $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($i, 2, "0", STR_PAD_LEFT)
                     && !in_array($lookForShift->shift_content_id, $lookForShiftsLoaded[$i-1])) {
                    $lookForShiftsLoaded[$i-1][$pointer++]=$lookForShift->shift_content_id;
                }
            }
        }
        return view('admin.lookForCreate', compact('shift_contents', "year", "month", "lookForShiftsLoaded", "lookForShifts"));
    }

    public function lookForStore(Request $request){
        for ($i=1; $i <= 31; $i++) {
            $str = $request["year"]."-".str_pad($request["month"],2, "0",STR_PAD_LEFT)."-".str_pad($i,2, "0",STR_PAD_LEFT);
            LookForShift::where("date", $str)->delete();

            for ($j=1; $j <= 4; $j++) {
                if ($request["shift_".$i."_".$j] != 0) {
                    LookForShift::create([
                        "date"=>$str,
                        "shift_content_id"=>$request["shift_".$i."_".$j],
                    ]);
                }
            }
        }
        return redirect()->route('admin.menu');
    }
}
