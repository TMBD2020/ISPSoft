<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use PDF;
use App\Models\Bills;

class CATVCollectionController extends Controller
{
    protected  $bills = "catb_bills";
    protected  $clients = "catb_clients";
    protected  $employees = "employees";
    protected  $zones = "zones";

    public function graph(){
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $employees = DB::table($this->employees)->get();
        $zones = DB::table($this->zones)->where("zone_type",2)->get();
        return view("back.reports.catv.graph.collection",compact("date_from","date_to","employees","zones"));
    }

    public function index(){
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $employees = DB::table($this->employees)->get();
        $zones = DB::table($this->zones)->where("zone_type",2)->get();
        return view("back.reports.catv.collection.index",compact("date_from","date_to","employees","zones"));
    }

    public function searchCollectionReport(Request $request){
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));
        $search_collected_by = $request->collected_by;
        $search_zone_id = $request->zone_id;

        if($search_collected_by!=0){
            $where = [
                'receive_by'   =>  $search_collected_by,
                "bill_status"    =>  1,
                //"zone_type"=>2
            ];
        }
        if($search_zone_id!=0){
            $where = [
                //'zone_id'       =>  $search_zone_id,
                "bill_status"   =>  1,
               // "zone_type"=>2
            ];
        }

        if($search_zone_id!=0 and $search_collected_by!=0){
            $where = [
                'receive_by'    =>  $search_collected_by,
              //  'zone_id'       =>  $search_zone_id,
                "bill_status"   =>  1,
             //   "zone_type"=>2
            ];
        }
        if($search_zone_id==0 and $search_collected_by==0){
            $where = [
                "bill_status"        =>  1,
               // "zone_type"=>2
            ];
        }

        $collection = Bills::whereHas("client_cat",function($q)use($search_zone_id){

            if($search_zone_id){
               $q->where(['zone_id'       =>  $search_zone_id,]);
           }
        })
           // ->select("bill_id","client.client_name as client_name","client.client_id as client_id","discount_amount",
           //     "receive_date","receive_amount","receive.emp_name as receive_name","receive.emp_id as receiver_id","zone_name_en")
//            ->leftJoin($this->clients." as client","client.id","=",$this->bills.".client_id")
//            ->leftJoin($this->employees." as receive","receive.id","=",$this->bills.".receive_by")
//            ->leftJoin($this->zones,$this->zones.".id","=","client.zone_id")
            ->where($where)
            ->where('client_type',2)
            ->whereBetween("receive_date",[$date_from,$date_to])
            ->orderBy("receive_date","asc")
            ->get();
        $collection_data=[];
        if(count($collection)>0){
            foreach ($collection as $val) {
                $collection_data[]=[
                    "receive_date"=>$val->receive_date,
                    "zone_name_en"=>$val->client_cat->zone?$val->client_cat->zone->zone_name_en:'',
                    "client_name"=>$val->client_cat->client_name,
                    "client_id"=>$val->client_cat->client_id,
                    "receive_amount"=>$val->receive_amount,
                    "discount_amount"=>$val->discount_amount,
                    "receiver_id"=>$val->employee->emp_id,
                    "receive_name"=>$val->employee->emp_name,
                ];
            }

            echo json_encode([
                "collection"=>$collection_data,
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
                "bill_status"    =>  1,
                "zone_type"=>2
            ];
        }
        if($search_zone_id!=0){
            $where = [
                'zone_id'       =>  $search_zone_id,
                "bill_status"   =>  1,
                "zone_type"=>2
            ];
        }

        if($search_zone_id!=0 and $search_collected_by!=0){
            $where = [
                'receive_by'    =>  $search_collected_by,
                'zone_id'       =>  $search_zone_id,
                "bill_status"   =>  1,
                "zone_type"=>2
            ];
        }
        if($search_zone_id==0 and $search_collected_by==0){
            $where = [
                "bill_status"        =>  1,
                "zone_type"=>2
            ];
        }
        $collection = Bills::query()
            ->select("bill_id","client.client_name as client_name","client.client_id as client_id","discount_amount",
                "receive_date","receive_amount","receive.emp_name as receive_name","receive.emp_id as receiver_id","zone_name_en")
            //->leftJoin($this->clients." as client","client.id","=",$this->bills.".client_id")
            ->leftJoin($this->employees." as receive","receive.id","=",$this->bills.".receive_by")
            ->leftJoin($this->zones,$this->zones.".id","=","client.zone_id")
            ->where($where)
            ->where('client_type',2)
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
            return view('back.reports.catv.collection.collection_pdf',compact('all_collection','operation'));
        }else{
            $pdf = PDF::loadView('back.reports.catv.collection.collection_pdf', compact('all_collection','operation'));
            return $pdf->download($file_name);
        }
    }

}
