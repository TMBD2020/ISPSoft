<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CATVStationImport;

class CATVStationController extends Controller
{
    public function stationImport()
    {
        return view('back.excel.catv_station');
    }

    public function fileImport(Request $request)
    {
        Excel::import(new CATVStationImport, $request->file('file')->store('temp'));
        return back()->with("msg","Import Successfully");
    }
}
