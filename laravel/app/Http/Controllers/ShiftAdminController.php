<?php

namespace App\Http\Controllers;

use App\Models\LookForShift;
use App\Models\ShiftContent;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ShiftAdminController extends Controller
{
    public function show(){
        return view('admin.menu');
    }

    public function placeIndex(){
        $shift_contents = ShiftContent::orderBy("place", "asc")->get();
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

    public function lookForShiftsLoaded($countOfDate, $lookForShifts, $year, $month): array
    {
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
        $lookForShifts = LookForShift::whereYear('date',$year)->whereMonth('date',$month)->get();
        $shift_contents = ShiftContent::orderBy("place", "asc")->orderBy("time", "asc")->get();

        $countOfDate = $this->countOfDate($year, $month);

        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);

        $lookForShiftsLoaded=$this->lookForShiftsLoaded($countOfDate, $lookForShifts, $year, $month);

        return view('admin.lookForCreate', compact('shift_contents', "year", "month", "lookForShiftsLoaded", "lookForShifts", "countOfDate", "daysOfWeek"));
    }

    public function lookForStore(Request $request){
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
        $lookForShifts = LookForShift::whereYear('date',$year)->whereMonth('date',$month)->get();
        $countOfDate = $this->countOfDate($year, $month);
        $daysOfWeek = $this->daysOfWeek($year, $month, $countOfDate);
        $lookForShiftsLoaded=$this->lookForShiftsLoaded($countOfDate, $lookForShifts, $year, $month);

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
                    $sheet->setCellValue($column++.($i+3), "【".$shiftContent->place.$shiftContent->time."】");
                } else {
                    break;
                }

            }
        }
        $sheet->getStyle('D4:G34')->getFont()->setSize(9);
        $sheet->getStyle('B4:G34')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:G34')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $newFileName = 'exported-file.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $newFileName));
        return response()->download(storage_path('app/public/' . $newFileName));
    }
}
