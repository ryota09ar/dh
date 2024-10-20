<?php

namespace App\Http\Controllers;

use App\Models\ConfirmedYearMonth;
use App\Models\DecideShift;
use App\Models\ExpiredYearMonth;
use App\Models\LookForShift;
use App\Models\RequestCount;
use App\Models\RequestShift;
use App\Models\ShiftContent;
use App\Models\User;
use App\Services\UserService;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ShiftAdminController extends Controller
{
    public function show(){
        DecideShift::where("date", "<", now()->subyear())->delete();
        RequestShift::where("date", "<", now()->submonth(2))->delete();
        RequestCount::where("created_at", "<", now()->submonth(2))->delete();
        LookForShift::where("date", "<", now()->submonth(2))->delete();
        ConfirmedYearMonth::where("created_at", "<", now()->submonth())->delete();
        ExpiredYearMonth::where("created_at", "<", now()->submonth())->delete();
        User::where("created_at", "<", now()->subyear(6))->delete();
        return view('admin.menu');
    }

    //edit place
    public function placeIndex(){
        $shift_contents = ShiftContent::orderBy("place", "asc")->get();
        if (ShiftContent::all()->count() > 15) {
            return redirect()->back()->withErrors(["overCount"=>"追加はせず既存のシフト場所を編集してください"]);
        }
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

    //look for shift
    public function countOfDate($year, $month): int
    {
        $date = new DateTime("$year-$month-01");
        return (int)$date->format("t");
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public function lookForCreate(Request $request){
        $year = $request['year'];
        $month = $request['month'];
        $shift_contents = ShiftContent::orderBy("place", "asc")->orderBy("time", "asc")->get();
        $countOfDate = $this->countOfDate($year, $month);
        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);
        $lookForShiftsLoaded = LookForShift::lookForShiftsLoaded($year, $month, $countOfDate);

        return view('admin.lookForCreate', compact('shift_contents', "year", "month", "lookForShiftsLoaded", "countOfDate", "daysOfWeek"));
    }

    public function lookForStore(Request $request){
        if (ConfirmedYearMonth::is_confirmed($request["year"], $request["month"])) {
            return redirect()->back()->withErrors(["yearMonth"=>"すでに募集が確定しているので変更できません"]);
        }
        for ($i=1; $i <= 31; $i++) {
            $str = $request["year"]."-".str_pad($request["month"],2, "0",STR_PAD_LEFT)."-".str_pad($i,2, "0",STR_PAD_LEFT);
            LookForShift::where("date", $str)->delete();

            for ($j=1; $j <= 4; $j++) {
                if ($request["shift_".$i."_".$j] != 0 && !(LookForShift::where("date", $str)->where("shift_content_id", $request["shift_".$i."_".$j])->exists())) {
                    LookForShift::create([
                        "date"=>$str,
                        "shift_content_id"=>$request["shift_".$i."_".$j],
                    ]);
                }
            }
        }
        return redirect()->route('admin.menu');
    }

    /**
     * @throws Exception
     */
    public function exportLookForShiftsToExcel(Request $request){
        $year = $request['year'];
        $month = $request['month'];
        $countOfDate = $this->countOfDate($year, $month);
        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);
        $lookForShiftsLoaded = LookForShift::lookForShiftsLoaded($year, $month, $countOfDate);

        $templatePath = storage_path('app/templates/dh.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("A1", $year);
        $sheet->setCellValue("B3", $month);

        for ($i=1;$i<=$countOfDate;$i++){
            $sheet->setCellValue("B".($i+3), $i);
            $sheet->setCellValue("C".($i+3), $daysOfWeek[$i]);
            if ($daysOfWeek[$i]=="土"){
                $sheet->getStyle("C".($i+3))->getFont()->getColor()->setRGB(Color::COLOR_BLUE);
            } else if ($daysOfWeek[$i]=="日"){
                $sheet->getStyle("C".($i+3))->getFont()->getColor()->setRGB(Color::COLOR_RED);
            }
            $column="D";
            for ($j=0;$j<count($lookForShiftsLoaded[$i]);$j++){
                $k=$lookForShiftsLoaded[$i][$j];
                if ($k!=0){
                    $shiftContent=ShiftContent::find($k);
                    $shiftPlaceChar = "【".$shiftContent->place.$shiftContent->time."】";
                    $sheet->setCellValue($column.($i+3), $shiftPlaceChar);
                    if (mb_strlen($shiftPlaceChar) > 13){
                        $sheet->getStyle($column++.($i+3))->getFont()->setSize(7.5);
                    } else {
                        $sheet->getStyle($column++.($i+3))->getFont()->setSize(9);
                    }
                } else {
                    break;
                }

            }
        }
        $sheet->getStyle('B4:G34')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:G34')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $newFileName = "look_for_shift_{$year}_{$month}.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $newFileName));

        return response()->download(storage_path('app/public/' . $newFileName));
    }

    public function lookForConfirmation(Request $request)
    {
        if (ConfirmedYearMonth::is_confirmed($request["year"], $request["month"])) {
            if (RequestShift::whereYear("date", $request["year"])->whereMonth("date", $request["month"])->exists()) {
                return redirect()->back()->withErrors(["yearMonth"=>"すでに募集が確定しています"]);
            } else {
                ConfirmedYearMonth::where("year", $request["year"])->where("month", $request["month"])->delete();
                return redirect()->back();
            }

        }
        if (!LookForShift::whereYear("date", $request["year"])->whereMonth("date", $request["month"])->exists()) {
            return redirect()->back()->withErrors(["yearMonth"=>"どこか募集してください"]);
        }
        ConfirmedYearMonth::create([
            "year"=>$request["year"],
            "month"=>$request["month"],
        ]);

        return redirect()->route("admin.menu");
    }

    //decide shift
    public function decideCreate(Request $request){
        $year = $request['year'];
        $month = $request['month'];
        $countOfDate = $this->countOfDate($year, $month);
        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);
        $lookForShiftIdsLoaded = LookForShift::lookForShiftIdsLoaded($year, $month, $countOfDate);
        $requestShiftsLoaded = RequestShift::requestShiftsLoaded($year, $month, $countOfDate);
        $requestedUsers = RequestShift::requestedUsers($year, $month);

        return view("admin.decideCreate", compact('year', 'month', 'countOfDate', 'daysOfWeek', "requestShiftsLoaded", "lookForShiftIdsLoaded", "requestedUsers"));
    }

    public function decideStore(Request $request){
        if (!ExpiredYearMonth::is_expired($request["year"], $request["month"])) {
            return redirect()->back()->withErrors(["yearMonth"=>"シフトを編集するには募集を締め切ってください"]);
        }
        $year = $request['year'];
        $month = $request['month'];
        $countOfDate = $this->countOfDate($year, $month);
        DecideShift::whereYear("date", $year)->whereMonth("date", $month)->delete();
        for ($i=1;$i<=$countOfDate;$i++){
            $index_number = 0;
            $makeDhByOneself = $request->input("makeDhByOneself_$i", []);
            foreach($request->input("decideShifts_$i", []) as $decideShiftId){
                $requestShift=RequestShift::find($decideShiftId);
                DecideShift::create([
                    "user_id" => $requestShift->user_id,
                    "date" => $requestShift->date,
                    "place" => $requestShift->lookForShift->shiftContent->place,
                    "time" => $requestShift->lookForShift->shiftContent->time,
                    "makeDhByOneself" => $makeDhByOneself[$index_number++],
                ]);
            }
        }

        return redirect()->route("admin.menu");
    }

    public function decideExpiration(Request $request){
        if (!ConfirmedYearMonth::is_confirmed($request["year"], $request["month"])) {
            return redirect()->back()->withErrors(["yearMonth"=>"先にシフト募集を確定させてください"]);
        }
        if (ExpiredYearMonth::is_expired($request["year"], $request["month"])) {
            ExpiredYearMonth::where("year", $request["year"])->where("month", $request["month"])->delete();
            return redirect()->back();
        }
        if (!RequestShift::existsYearMonth($request["year"], $request["month"])) {
            return redirect()->back()->withErrors(["yearMonth"=>"募集している人がいません"]);
        }
        ExpiredYearMonth::create([
            "year"=>$request['year'],
            "month"=>$request['month'],
        ]);
        return redirect()->back();
    }

    //decided shift index
    public function decidedIndex(Request $request){
        $year = $request['year'];
        $month = $request['month'];
        $countOfDate = $this->countOfDate($year, $month);
        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);
        $decidedShifts = DecideShift::whereYear("date", $year)->whereMonth("date", $month)->get();
        $lookForShiftIdsLoaded=LookForShift::lookForShiftIdsLoaded($year, $month, $countOfDate);
        DecideShift::lookForSortByDecided($lookForShiftIdsLoaded, $countOfDate);

        return view("admin.decidedIndex", compact('year', 'month', 'countOfDate', 'daysOfWeek', 'decidedShifts', 'lookForShiftIdsLoaded'));
    }

    public function exportDecidedShiftsToExcel(Request $request){
        $year = $request['year'];
        $month = $request['month'];
        $countOfDate = $this->countOfDate($year, $month);
        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);
        $decidedShifts = DecideShift::whereYear("date", $year)->whereMonth("date", $month)->get();
        $lookForShiftIdsLoaded=LookForShift::lookForShiftIdsLoaded($year, $month, $countOfDate);
        DecideShift::lookForSortByDecided($lookForShiftIdsLoaded, $countOfDate);

        $templatePath = storage_path('app/templates/dh.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("A1", $year);
        $sheet->setCellValue("B3", $month);

        for ($i=1;$i<=$countOfDate;$i++){
            $sheet->setCellValue("B".($i+3), $i);
            $sheet->setCellValue("C".($i+3), $daysOfWeek[$i]);
            if ($daysOfWeek[$i]=="土"){
                $sheet->getStyle("C".($i+3))->getFont()->getColor()->setRGB(Color::COLOR_BLUE);
            } else if ($daysOfWeek[$i]=="日"){
                $sheet->getStyle("C".($i+3))->getFont()->getColor()->setRGB(Color::COLOR_RED);
            }
            $column="D";
            for ($j=0;$j<count($lookForShiftIdsLoaded[$i]);$j++){
                $k=$lookForShiftIdsLoaded[$i][$j];
                if ($k!=0){
                    $place=LookForShift::find($k)->shiftContent->place;
                    $time=LookForShift::find($k)->shiftContent->time;
                    $cellValue="【".$place.$time."】";
                    foreach ($decidedShifts as $decidedShift){
                        if($decidedShift->place==$place && $decidedShift->time==$time && $decidedShift->date==\App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->date){
                            $cellValue.=" ".UserService::return_name($decidedShift->user_id);
                            if ($decidedShift->makeDhByOneself){
                                $cellValue.="○";
                            }
                        }
                    }
                    $sheet->setCellValue($column.($i+3), $cellValue);
                    if(mb_strlen($cellValue)>23){
                        $sheet->getStyle($column++.($i+3))->getFont()->setSize(5);
                    } else if(mb_strlen($cellValue)>19){
                        $sheet->getStyle($column++.($i+3))->getFont()->setSize(5.5);
                    } else{
                        $sheet->getStyle($column++.($i+3))->getFont()->setSize(6);
                    }

                } else {
                    break;
                }

            }
        }
        $sheet->getStyle('B4:G34')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:G34')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B4:G34')->getFont()->setName('Arial Unicode MS');

        $newFileName = "decided_shift_{$year}_{$month}.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $newFileName));

        return response()->download(storage_path('app/public/' . $newFileName));
    }
}
