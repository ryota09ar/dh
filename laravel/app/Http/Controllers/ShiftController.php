<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftContent;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function create()
    {
        $shift_contents=ShiftContent::all();
        return view('shift.create', compact('shift_contents'));
    }

    public function store(Request $request){
        for ($i=1; $i <= 31; $i++) {
            if ($request["shift_content_".$i]!=0){
                Shift::create([
                    "shift_date" => $request["year"]."-". str_pad($request["month"], 2, '0', STR_PAD_LEFT)
                        ."-".str_pad($i, 2, '0', STR_PAD_LEFT),
                    'shift_content_id' => $request["shift_content_".$i]
                ]);
            }
        }
        return redirect()->back();
    }
}
