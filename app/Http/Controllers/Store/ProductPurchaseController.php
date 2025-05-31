<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ProductBrands;
use App\Models\StoreProducts;
use App\Models\Expenses;
use App\Models\Vendors;
use App\Models\PurchaseVouchers;
use App\Models\Employees;
use Illuminate\Http\Request;
use DB;
use Auth;

class ProductPurchaseController extends Controller
{
    public function index(){
        $vendors = Vendors::all();
        $stock_products = StoreProducts::all();
        return view("back.store.purchase_product", compact("vendors","stock_products"));
    }

    public function purchase_product(Request $request){

        $product_id     = $request->product_id;
        $product_qty    = $request->product_qty;
        $fixed_price    = $request->fixed_price;
        $unit_price     = $request->unit_price;
        $serial_no      = $request->serial_no;

        $purchase_date  = date("Y-m-d", strtotime(str_replace("/","-",$request->store_date)));
        $voucher_no     = $request->voucher_no;
        $purchaser_id   = $request->purchaser_id;
        $vendor_id      = $request->vendor_id;
        $vendor_memo_no = $request->vendor_memo_no;
        $product_status = $request->product_status;
        $other_expense  = $request->other_expense;
        $remarks        = $request->remarks;
        $created_at     = date("Y-m-d H:i");



        $total_cost = array_sum($fixed_price)+$other_expense;
        $expense_data = [
            "company_id"=>\Settings::company_id(),
            "expense_head_id"=>1,
            "responsible_person"=>$purchaser_id,
            "expense_voucher_no"=>$voucher_no,
            "expense_date"=>$purchase_date,
            "expense_amount"=>$total_cost,
            "expense_note"=>implode(",",$product_id)."\n"."total: ". $total_cost,
            "created_at"=>$created_at,
            "updated_at"=>$created_at,
        ];

        $voucher_data=$store_data=[];
        for($i=0; $i<count($product_id) ;  $i++){
            $voucher_data[] = [
                "product_id"=>$product_id[$i],
                "product_serial"=>$serial_no[$i],
                "fixed_price"=>$fixed_price[$i],
                "unit_price"=>$unit_price[$i],
                "product_qty"=>$product_qty[$i],
                "product_status"=>$product_status,
                "created_at"=>$created_at,
                "updated_at"=>$created_at,
            ];


            $old_records = StoreProducts::query()
                ->where("ref_product_id",$product_id[$i])
                ->orderBy("id","desc")->first();
            if($old_records){
                $available_product=$old_records->product_available+$product_qty[$i];
            }else{
                $available_product = $product_qty[$i];
            }
            $store_data[] = [
                "company_id"=>\Settings::company_id(),
                "ref_voucher_id"=>$voucher_no,
                "ref_product_id"=>$product_id[$i],
                "product_in"=>$product_qty[$i],
                "product_out"=>0,
                "product_damage"=>0,
                "product_available"=>$available_product,
                "record_date"=>$purchase_date,
                "voucher_status"=>"purchase",
                "created_at"=>$created_at,
                "updated_at"=>$created_at,
            ];
        }
        $purchase_data = [
            "company_id"=>\Settings::company_id(),
            "voucher_no"=>$voucher_no,
            "vendor_id"=>$vendor_id,
            "vendor_memo_no"=>$vendor_memo_no,
            "purchaser_id"=>$purchaser_id,
            "total_price"=>array_sum($fixed_price),
            "other_expense"=>$other_expense,
            "purchase_date"=>$purchase_date,
            "purchase_details"=>json_encode($voucher_data),
            "remarks"=>$remarks,
            "created_at"=>$created_at,
            "updated_at"=>$created_at,
        ];
        if($voucher_data){
            $result_vouchers = PurchaseVouchers::query()->insert($purchase_data);
            if($result_vouchers){
                $result_records = StoreProducts::query()->insert($store_data);
                if($result_records){
                    $result_expense = Expenses::query()->insert($expense_data);
                    if($result_expense){
                        echo 1;
                    }else{
                        echo 0;
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
}
