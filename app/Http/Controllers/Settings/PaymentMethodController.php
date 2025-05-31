<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethods;
use Auth;
use DB;

class PaymentMethodController extends Controller
{
    protected $payment_method = "payment_method";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        return view("back.settings.payment_method");
    }

    public function save_payment_method(Request $request)
    {
        if($request->action==1){
            $result = PaymentMethods::query()->insert(
                [
                    "company_id"=> \Settings::company_id(),
                    "payment_name"     => $request->payment_name,
                    "payment_account"     => $request->payment_account,
                    "note"     => $request->note,
                    "is_active"     => 1,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "payment_name"     => $request->payment_name,
                "payment_account"     => $request->payment_account,
                "note"     => $request->note,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = PaymentMethods::query()
                ->where(["company_id"=>\Settings::company_id()])->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function paymentMethodList(Request $request)
    {

        $columns = array(
            1 =>'payment_name',
        );

        $totalData = PaymentMethods::query()
            ->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = PaymentMethods::query()
                ->where(["company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   PaymentMethods::query()
                ->where(["company_id"=>\Settings::company_id()])                
                ->Where('payment_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  PaymentMethods::query()
                ->where(["company_id"=>\Settings::company_id()])
                ->Where('payment_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->payment_name;
                $nestedData[] = $post->payment_account;
                $nestedData[] = $post->note;
                $nestedData[] = '<div class="btn-group align-top" role="group">
                <button id="' . $post->id . '" class="update btn btn-primary btn-sm badge"> <span class="ft-edit"></span></button> 
            </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    public function paymentMethodUpdate(Request $request)
    {
        $result =  PaymentMethods::find($request->id);
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function paymentMethodDelete(Request $request)
    {
        $result = PaymentMethods::find($request->id);
        if($result){
            $result = $result->delete();
            echo 1;
        }else{
            echo 0;
        }
    }
}
