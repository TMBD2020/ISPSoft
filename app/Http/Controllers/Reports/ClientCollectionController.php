<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;
use App\Models\Bills;
use App\Models\BillReceives;
use App\Models\Employees;
use App\Models\Zones;


class ClientCollectionController extends Controller
{
    protected  $bills = "bills";
    protected  $clients = "clients";
    protected  $employees = "employees";
    protected  $zones = "zones";

    public function index(){
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $employees = Employees::where("company_id",\Settings::company_id())->get();
        $zones = Zones::where("zone_type",1)->where("company_id",\Settings::company_id())->get();
        return view("back.reports.client_collection.index",compact("date_from","date_to","employees","zones"));
    }

   
    public function searchCollectionReport(Request $request){
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));
        $search_collected_by = $request->collected_by;
        $search_zone_id = $request->zone_id;

        
        if($search_collected_by!=0){
            $where = [
                'receive_by'   =>  $search_collected_by,
                "bill_receives.company_id"=>\Settings::company_id()
            ];
        }else{
            $where = [
                "bill_receives.company_id"=>\Settings::company_id()
            ];
        }

        $collection = BillReceives::whereHas("client",function($q) use($search_zone_id){
            if($search_zone_id!=0){
                $q->where('zone_id',$search_zone_id);
            }
            $q->where('company_id',\Settings::company_id());
        })->where($where)
            ->whereBetween("receive_date",[$date_from,$date_to])
            ->orderBy("receive_date","asc")
            ->get();

        if(count($collection)>0){

            $data=[];
            foreach ($collection as $k=>$d) {
                $data[] = [
                    "client_id"=>$d->client->client_id,
                    "client_name"=>$d->client->client_name,
                    "receive_amount"=>$d->receive_amount,
                    "discount_amount"=>$d->discount_amount,
                    "permanent_discount"=>$d->permanent_discount,
                    "receive_date"=>$d->receive_date,
                    "zone_name_en"=>$d->client->zone->zone_name_en,
                     "receive_name"=> !isset($d->employee->emp_name) ? ($d->admin?$d->admin->name:"") : $d->employee->emp_name,
                    "receiver_id"=> !isset($d->employee->emp_name) ? ($d->admin?$d->admin->user_id:"") : $d->employee->emp_id,
                ];
            }

            echo json_encode([
                "collection"=>$data,
                "dates"=>[
                        "date_from" =>date("d.m.Y", strtotime($date_from)),
                        "date_to"   =>date("d.m.Y", strtotime($date_to))
                    ],
            ]);
        }else{
            echo json_encode([
                "collection"=>0,
                "dates"=>[
                    "date_from" =>date("d.m.Y", strtotime($date_from)),
                    "date_to"   =>date("d.m.Y", strtotime($date_to))
                ],
            ]);
        }
    }

    public function downloadPDF(Request $request) {

        $date_from= explode(".",$request->date_from);
        $date_to= explode(".",$request->date_to);

        $date_from = $date_from[2]."-".$date_from[1]."-".$date_from[0];
        $date_to = $date_to[2]."-".$date_to[1]."-".$date_to[0];
        $search_collected_by = $request->collected_by;
        $search_zone_id = $request->zone_id;

        if($search_collected_by!=0){
            $where = [
                'receive_by'   =>  $search_collected_by,
                "bills.company_id"=>\Settings::company_id()
            ];
        }else{
            $where = [
                "bills.company_id"=>\Settings::company_id()
            ];
        }
        $collection = Bills::whereHas("client",function($q) use($search_zone_id){
            if($search_zone_id!=0){
                $q->where('zone_id',$search_zone_id);
            }
            $q->where('company_id',\Settings::company_id());
        })->where($where)
            ->whereBetween("receive_date",[$date_from,$date_to])
            ->orderBy("receive_date","asc")
            ->get();

        $file_name =  "client_collection_".rand(0,9999).".pdf";
        $operation = $request->operation;

        $all_collection = [
            "collection"=>$collection,
            "dates"=>[
                "date_from" =>date("d.m.Y", strtotime($date_from)),
                "date_to"   =>date("d.m.Y", strtotime($date_to))
            ],
        ];
        if($operation=="Print"){
            return view('back.reports.client_collection.collection_pdf',compact('all_collection','operation'));
        }else{
            $pdf = PDF::loadView('back.reports.client_collection.collection_pdf', compact('all_collection','operation'));
            return $pdf->download($file_name);
        }
    }

}
