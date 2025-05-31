<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

class ClientCollectionSummeryController extends Controller
{
    protected  $bills = "bills";
    protected  $clients = "clients";
    protected  $employees = "employees";
    protected  $zones = "zones";

    public function index(){
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $zones = DB::table($this->zones)->where("zone_type",1)->where("company_id",\Settings::company_id())->get();
        return view("back.reports.client_collection_summery.index",compact("date_from","date_to","zones"));
    }

    public function searchCollectionReport(Request $request){
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));
        $search_zone_id = $request->zone_id;

        if($search_zone_id!=0){
            $where = [
                'zone_id'       =>  $search_zone_id,
                "bills.company_id"=>\Settings::company_id()
            ];
        }

        if($search_zone_id==0){
            $where = [
                "bills.company_id"=>\Settings::company_id()
            ];
        }
        $zones = DB::table($this->zones)->where("zone_type",1)->where("company_id",\Settings::company_id())->get();
        $collection = DB::table($this->bills)
            ->select(DB::raw("(sum(receive_amount)) as collections,client.zone_id,sum(connection_charge) as conn_fee"))
            ->leftJoin($this->clients." as client","client.id","=",$this->bills.".client_id")
            ->leftJoin($this->zones,$this->zones.".id","=","client.zone_id")
            ->where($where)
            ->whereBetween("bills.receive_date",[$date_from,$date_to])
            ->groupBy("client.zone_id")
            //->orderBy("bills.receive_date","asc")
            ->get();

        if(count($collection)>0){
            echo json_encode([
                "collection"=>$collection,
                "zones"=>$zones,
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
        $search_zone_id = $request->zone_id;
        if($search_zone_id!=0){
            $where = [
                'zone_id'       =>  $search_zone_id,
                "bills.company_id"=>\Settings::company_id()
            ];
        }

        if($search_zone_id==0){
            $where = [
                "bills.company_id"=>\Settings::company_id()
            ];
        }
        $zones = DB::table($this->zones)->where("zone_type",1)->where("company_id",\Settings::company_id())->get();
        $collection = DB::table($this->bills)
            ->select(DB::raw("(sum(receive_amount)-sum(connection_charge)) as collections,client.zone_id,sum(connection_charge) as conn_fee"))
            ->leftJoin($this->clients." as client","client.id","=",$this->bills.".client_id")
            ->leftJoin($this->zones,$this->zones.".id","=","client.zone_id")
            ->where($where)
            ->whereBetween("bills.receive_date",[$date_from,$date_to])
            ->groupBy("client.zone_id")
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
            return view('back.reports.client_collection_summery.collection_pdf',compact('all_collection','operation',"zones"));
        }else{
            $pdf = PDF::loadView('back.reports.client_collection_summery.collection_pdf', compact('all_collection','operation',"zones"));
            return $pdf->download($file_name);
        }
    }
}
