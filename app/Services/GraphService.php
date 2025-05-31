<?php
/**
 * Created by PhpStorm.
 * User: iQ
 * Date: 5/1/2022
 * Time: 1:08 AM
 */

namespace App\Services;

use App\Models\Bills;
use DB;

class GraphService
{
    public function paymentMethod()
    {
        $bills =Bills::query()->select(DB::raw("sum(receive_amount) receive,payment_method_id"))
            ->where("receive_amount",">","0")->where("company_id",\Settings::company_id())->groupBy("payment_method_id")->get();

        $billss=[];
        foreach ($bills as $bill) {
            $new=[];
            $new["payment"]=$bill->paymentMethod->payment_name;
            $new["receive"]=$bill->receive;
            $billss[]=$new;
        }


        return (object) $billss;
    }
}