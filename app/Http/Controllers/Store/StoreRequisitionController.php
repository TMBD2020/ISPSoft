<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class StoreRequisitionController extends Controller
{
    protected $users = "users";
    protected $stock_products = "store_products";
    protected $product_brands = "product_brands";
    protected $employees = "employees";
    protected $vendors = "vendors";
    protected $store_records = "store_records";
    protected $tickets = "tickets";
    protected $requisition_orders = "requisition_orders";
    protected $requisition_details = "requisition_details";

    public function index(){
        $vendors = DB::table($this->vendors)->get();
        $stock_products = DB::table($this->stock_products)->get();
        $tickets = DB::table($this->tickets)->where("ticket_status",1)->get();
        return view("back.store.store_requisition", compact("tickets","vendors","stock_products"));
    }

    public function available_product(Request $request){
        $id=$request->id;
        $product = DB::table($this->store_records)
            ->where("ref_product_id",$id)
            ->orderBy("id","desc")->first();
        if($product){
            echo $product->product_available;
        }else{
            echo 0;
        }
    }

    public function requisition_product(Request $request){

        $product_id     = $request->product_id;
        $product_qty    = $request->product_qty;

        $requisition_date  = date("Y-m-d", strtotime(str_replace("/","-",$request->store_date)));
        $ticket_no     = $request->ticket_no;
        $voucher_no     = mt_rand(100000, 999999);
        $purchaser_id   = $request->purchaser_id;
        $remarks        = $request->remarks;
        $created_at     = date("Y-m-d H:i");

        $approval=0;
        if(Auth::user()->user_type=='admin'){
            $approval=1;
        }

        $purchase_data = [
            "voucher_no"=>$voucher_no,
            "ticket_no"=>implode(",",$ticket_no),
            "purchaser_id"=>$purchaser_id,
            "requisition_date"=>$requisition_date,
            "approval"=>$approval,//auto approve for test, make 0 for approval
            "remarks"=>$remarks,
            "created_at"=>$created_at,
            "updated_at"=>$created_at,
        ];

        $voucher_data=$store_data=[];
        for($i=0; $i<count($product_id) ;  $i++){

            $voucher_data[] = [
                "ref_voucher_no"=>$voucher_no,
                "product_id"=>$product_id[$i],
                "product_qty"=>$product_qty[$i],
                "created_at"=>$created_at,
                "updated_at"=>$created_at,
            ];

            if($approval==1){
                $old_records = DB::table($this->store_records)
                    ->where("ref_product_id",$product_id[$i])
                    ->orderBy("id","desc")->first();
                if($old_records){
                    if($old_records->product_available>0){
                        $available_product=$old_records->product_available-$product_qty[$i];
                        $store_data[] = [
                            "ref_voucher_id"=>$voucher_no,
                            "ref_product_id"=>$product_id[$i],
                            "product_in"=>0,
                            "product_out"=>$product_qty[$i],
                            "product_damage"=>0,
                            "product_available"=>$available_product,
                            "record_date"=>$requisition_date,
                            "voucher_status"=>"requisition",
                            "created_at"=>$created_at,
                            "updated_at"=>$created_at,
                        ];
                    }
                }
            }
        }

        if($voucher_data){
            $result_details = DB::table($this->requisition_details)->insert($voucher_data);
            if($result_details){
                $result_vouchers = DB::table($this->requisition_orders)->insert($purchase_data);
                if($result_vouchers){
                    if($approval==1){
                        $result_records = DB::table($this->store_records)->insert($store_data);
                        if($result_records){
                            echo 1;
                        }else{
                            echo 0;
                        }
                    }else{
                        echo 1;
                    }
                }else{
                    echo 0;
                }
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
    }

    public function requisitionNotification(){

        $total_req_order = DB::table($this->requisition_orders)->where("approval",0)->count();

        echo $total_req_order;
    }
}
