<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BillService;
use App\Models\Zones;
use App\Models\Employees;
use DB;
use PDF;

class BillsReportController extends Controller
{
    public function __construct(
        BillService $billService
    )
    {
        $this->billService = $billService;
    }
    public function index(){

        $due_bills=$this->billService->getBillGenerateReport([]);


        $employees = Employees::query()->where("company_id",\Settings::company_id())->get();
        $zones = Zones::query()->where("zone_type",1)->where("company_id",\Settings::company_id())->get();
        $zone_id=null;
        $month=date('m');
        $year=date('Y');
        return view("back.reports.bill.bill_generate_report",compact("month","year","due_bills","zones","employees","zone_id"));
    }
    public function filter(Request $request){

        $due_bills=$this->billService->getBillGenerateReport($request);


        $employees = Employees::query()->where("company_id",\Settings::company_id())->get();
        $zones = Zones::query()->where("zone_type",1)->where("company_id",\Settings::company_id())->get();
        $zone_id=$request->zone_id;
        $month=$request->month;
        $year=$request->year;
        return view("back.reports.bill.bill_generate_report",compact("month","year","due_bills","zones","employees","zone_id"));
    }
    
    public function isp_client(){
        $clients    =   [];
        $zones      = Zones::query()
            ->where("zone_type",1)->where("company_id",\Settings::company_id())
            ->get();
        $zone_id=$status=null;
        return view("back.reports.clients.isp",compact("clients","zones","zone_id","status"));
    }
    public function isp_client_search(Request $request){
       
        $zone_id=$request->zone_id;
        $status=$request->status;
        $clients    =   $this->billService->getIspClients($request);
        $zones      = Zones::query()
            ->where("zone_type",1)->where("company_id",\Settings::company_id())
            ->get();
        if($request->pdf==2){
            return view("back.reports.clients.isp",compact("clients","zones","zone_id","status"));
        }else{
ini_set('memory_limit', '-1');
            $pdf =PDF::loadView('back.reports.clients.isp_pdf',compact('clients'), [],  [
                'mode' => 'utf-8',
                'orientation' => 'L',
                'default_font_size'=> '12',
                'format' => 'A4',
                'title'      => 'ISP Clients',
            ]);
            return $pdf->stream('isp-clients.pdf');
        }
    }
}
