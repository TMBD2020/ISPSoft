<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Model\Bills;
use App\Model\Clients;
use App\Model\Packages;
use PDF;

class DueBillController extends Controller
{

    protected $bills = "bills";
    protected $clients = "clients";
    protected $packages = "packages";
    protected $zones = "zones";

    public function index(){

        $due_bills=DB::table($this->bills)
            ->select(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)+sum(permanent_discount_amount))) as payable,client_id"))
            //->where()
            ->groupBy("client_id")
            ->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)+sum(permanent_discount_amount)))"),">",0)
            ->get();
        //dd($due_bills);
        $clients = Clients::all();
        $zones = DB::table($this->zones)->where("zone_type",1)->get();
        return view("back.reports.due.due_bill",compact("due_bills","clients","zones"));
    }

    public function downloadPDF(Request $request) {

        $file_name =  "due_bill_".rand(0,9999).".pdf";
        $operation = $request->operation;

        $due_bills = DB::table($this->bills)
            ->select(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as payable,client_id"))
            ->groupBy("client_id")
            ->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)))"),">",0)
            ->get();
        $clients = Clients::all();
        $zones = DB::table($this->zones)->where("zone_type",1)->get();

        if($operation=="Print"){
            return view('back.reports.due.due_pdf',compact('due_bills','clients','zones','operation'));

        }else{
            $pdf = PDF::loadView('back.reports.due.due_pdf', compact('due_bills','clients','zones','operation'));
            return $pdf->download($file_name);
        }
    }

}
