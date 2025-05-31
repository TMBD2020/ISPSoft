<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatbClients;
use App\Models\Bills;
use App\Models\CatvPackages;
use App\Models\SmsHistory;
use App\Models\IspBillHistorys;
use App\Models\PaymentMethods;
use App\Models\Employees;
use Illuminate\Foundation\Auth\User;
use Auth;
use Carbon\Carbon;
use DB;

class CatBBillController extends Controller
{
    protected $employees = "employees";
    protected $clients = "catb_clients";
    protected $packages = "packages";
    protected $zones = "zones";
    protected $nodes = "nodes";
    protected $bills = "catv_bills";
    protected $clientType = 2;

    public  function auto_bill($month){
        $start = Carbon::now()->startOfMonth()->toDateString()." 00:00:00";
        $end = Carbon::now()->endOfMonth()->toDateString()." 23:00:00";

        $clients = CatbClients::query()
            ->select("catb_clients.id as cl_id","catb_clients.*")
            ->where("connection_mode",1)
            ->get();

        if($month==0){
            $month="m";
        }
        $today      = date("Y-$month-1");
        $previous_month = strtotime("-1 month ". $today);
        $this_month = date("m", $previous_month);
        $this_year  = date("Y", $previous_month);
        $data = $sms_data = $historyData = array();

        foreach ($clients as $client) {
            $bill_type = 1;//package bill/monthly bill

            $bill_exist = Bills::query()
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('client_type', 2)//catv
                ->count();
            $bill_count = $bill_exist+1;

            $bill_id = $this_year.$this_month.$bill_type.$bill_count.$client->cl_id;

            if($bill_exist==0){

                $catvPackages = CatvPackages::find($client->package_id);

                if($catvPackages){
                    $payable=$catvPackages->price;
                } else {
                    $payable = $client->mrp;
                }


                $new_data=array();
                $new_data["client_id"]                  = $client->cl_id;
                $new_data["client_initial_id"]          = $client->client_id;
                $new_data["bill_id"]                    = $bill_id;
                $new_data["bill_date"]                  = $today;
                $new_data["bill_month"]                 = $this_month;
                $new_data["bill_year"]                  = $this_year;
                $new_data["bill_type"]                  = $bill_type;
                $new_data["bill_status"]                = 0;
                $new_data["payable_amount"]             = $payable;
                $new_data["client_type"]                = 2;//catv
                $new_data["permanent_discount_amount"]  = 0;
                $new_data["created_at"]  = date("Y-m-d H:i");
                $data[]= $new_data;


                $history=array();
                $history["particular"]= "Monthly bill";
                $history["client_id"]= $new_data["client_id"];
                $history["bill_id"]= $new_data["bill_id"];
                $history["bill_year"]= $new_data["bill_year"];
                $history["bill_month"]= $new_data["bill_month"];
                $history["bill_amount"]= $new_data["payable_amount"];
                $history["receive_amount"]= 0;
                $history["client_type"]= 2;
                $history["created_at"]= date("Y-m-d H:i");
                $history["updated_at"]= date("Y-m-d H:i");
                $historyData[]=$history;

//                if($client->payment_alert_sms==1){
//                    $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
//                    $sms_text = "Dear ".$client->client_name." (".$client->client_id."), your last month bill ".$new_data["payable_amount"]."tk. has been due.";
//                    $cell_no        = $client->cell_no;
//                    $sms_count        = round(strlen($sms_text)/160)==0?1:round(strlen($sms_text)/160);
//                    $sms_type="english";
//                    $sms_status="Pending";
//                    $created_at = date("Y-m-d H:i");
//                    $sms_data[] = [
//                        "sms_receiver"=>$cell_no,
//                        "sms_sender"=>"Administrator",
//                        "sms_count"=>$sms_count,
//                        "sms_type"=>$sms_type,
//                        "sms_text"=>$sms_text,
//                        "sms_api"=>$sms_api,
//                        "sms_status"=>$sms_status,
//                        "sms_schedule_time"=>$created_at,
//                        "sent_time"=>$created_at,
//                        "created_at"=>$created_at
//                    ];
//
//                }


            }
        }
        //print_r($data);
        if($data){

            IF($historyData){
                IspBillHistorys::query()->insert($historyData);
            }

            $query = Bills::query()->insert($data);
            if($query){
                DB::table("sms_history")->insert($sms_data);
                echo 1;
            }else{
                echo 0;
            }
        } else {
            echo "created all bill";
        }

    }

    public function index()
    {
        $date_from = date("01/m/Y",strtotime("-1 month". date("Y-m-d")));
        $date_to = date("d/m/Y");
        $nodes = DB::table($this->nodes)->get();
        $payments =PaymentMethods::query()->where(["company_id"=>\Settings::company_id()])->get();
        if(in_array(auth()->user()->ref_role_id,[1,2])) {
            $employees = User::select("auth_id", "emp_name")
                ->leftJoin($this->employees, "auth_id", "=", "users.id")
                ->where("users.id", "<>", "1")
                ->where(["users.company_id" => \Settings::company_id(), "is_admin" => 1])
                ->get();
        }
        else{
            $employees = array();
        }
        return view("back.bill.catv_bill", compact("nodes","date_from","date_to","payments","employees"));
    }


    public function catv_bill_collect(Request $request)
    {
        $payable = $request->payable;
        $bills = IspBillHistorys::query()
            ->where(
                [
                    "company_id"     => \Settings::company_id(),
                    "client_id"     => $request->id,
                    "bill_status"   => 0,
                    "client_type"   => $this->clientType
                ]
            )
            ->get();

        $total_amount=$request->receive_amount+$request->discount_amount;//with discount

        foreach ($bills as $bill) {
            $bill_amount= $bill->bill_amount-$bill->receive_amount;
            if($total_amount>=$bill_amount){
                $his=[
                    "bill_status"       => 1,
                    "receive_amount"    => $bill->bill_amount,
                    // "particular"        => "cond1",
                    "updated_at"        => date("Y-m-d H:i:s")
                ];
                IspBillHistorys::query()->whereId($bill->id)->update($his);


            }
            if($total_amount>0 and $bill_amount>$total_amount and $bill->receive_amount>=0){
                $his=[
                    "bill_status"       => 0,
                    "receive_amount"    => $bill->receive_amount+$total_amount,
                    //"particular"        => "cond2",
                    "updated_at"        => date("Y-m-d H:i:s")
                ];
                IspBillHistorys::query()->whereId($bill->id)->update($his);
            }
            if(($bill_amount-$bill->receive_amount)==$total_amount){
                $his=[
                    "bill_status"       => 1,
                    "receive_amount"    => $total_amount,
                    //"particular"        => "cond3",
                    "updated_at"        => date("Y-m-d H:i:s")
                ];
                IspBillHistorys::query()->whereId($bill->id)->update($his);
            }
            $total_amount-=$bill_amount;
        }

        $result = Bills::query()->insert(
            [
                "company_id"        => \Settings::company_id(),
                "payment_method_id"        => $request->payment_method_id,
                "client_initial_id"        => $request->client_initial_id,
                "bill_id"           => uniqid(),
                "particular"        => 'Collection',
                "bill_status"       => 1,
                "client_type"       => $this->clientType,
                "client_id"         => $request->id,
                "receive_amount"    => $request->receive_amount,
                "discount_amount"   => $request->discount_amount,
                "note"              => $request->note,
                "receive_date"      => date("Y-m-d", strtotime(str_replace("/","-",$request->receive_date))),
                "bill_month"        => date("m", strtotime(str_replace("/","-",$request->receive_date))),
                "bill_year"         => date("Y", strtotime(str_replace("/","-",$request->receive_date))),
                "receive_by"        => $request->collected_by ,
                //"receive_by"        => auth()->user()->id,
                "updated_at"        => date("Y-m-d H:i:s")
            ]
        );
//
        if($result){
            //create welcome sms
            if(isset($request->payment_confirm_sms)){
                if($request->payment_confirm_sms==1){
                    $clients = CatbClients::where( "auth_id",$request->id)->first();
                    $bal=$request->receive_amount+$request->discount_amount;
                    $due=$payable-$bal;
                    // $sms_text = "Dear ".$clients->client_name.", your current month bill has been paid.";
                    // $sms_text = "Hello, Greetings from IMAXQ . We've successfully received your payment ".$total_amount."tk,still due is 2000tk ,date line is 15 august ,please pay your due 01993678660(bkash) Hotline:01841918091";
                    $sms_text = "Hello, Greetings from IMAXQ . We've successfully received your payment ".$bal."tk.";
                    if($due){
                        $sms_text.="\nStill due is ".$due."tk ,date line is ". date("d, F",strtotime(date("Y-m-d")."+1days "));
                    }
                    $sms_text.="\nHotline:01841918091";

                    $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
                    $sms_data=array(
                        "to"            =>  $clients->cell_no,
                        "sms_text"      =>  $sms_text,
                        "sms_from"      =>  "Payment Confirmation",
                        "sms_sender"    =>  Auth::user()->name,
                        "sms_type"      =>  "english",
                        "sms_api"       =>  $sms_api,
                        "schedule_time" =>  date("Y-m-d H:i:s")
                    );
                    app('App\Http\Controllers\SMS\SMSController')->master_save_sms($sms_data);
                }
            }
            echo 1;
        }else{
            echo 0;
        }

    }

    public function catv_all_bill(Request $request)
    {
        $client_type=2;
        $columns = [
            0 => 'id',
            1 => 'client_name',
        ];

        $date_from = date("Y-m-d",strtotime(str_replace("/","-",$request->date_from)));
        $date_to = date("Y-m-d",strtotime(str_replace("/","-",$request->date_to)));

        $totalData = Bills::query()
            ->select(
                DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
               // DB::raw("sum(receive_amount) as rcv_amount"),
                DB::raw("sum(discount_amount) as dis_amount"),
                "client_id"
            )
            ->where("company_id", \Settings::company_id())
            ->where("client_type",$client_type)
            ->groupBy('client_id')
            ->count();



        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Bills::query()
                ->select(
                    DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                    //DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("sum(discount_amount) as dis_amount"),
                    "client_id"
                )
                ->where("company_id", \Settings::company_id())
                ->where("client_type",$client_type)
                ->groupBy('client_id')
                ->orderBy("client_id", $dir)
                ->get();
        }

        else {
            $search = $request->input('search.value');
            $clinets = CatbClients::query()
                ->select("id")
                ->orWhere('client_id', "like", "%{$search}%")
                ->orWhere('client_name', 'like', "%{$search}%")
                ->orWhere('cell_no', 'LIKE', "%{$search}%")
                ->get();

            $posts = Bills::query()
                ->select(
                    DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                  //  DB::raw("sum(receive_amount) as rcv_amount"),
                    "client_id"
                )->where("client_type",$client_type);

            if (count($clinets) > 0) {
                $ids = [];
                foreach ($clinets as $clinetsss) {
                    $ids[] = $clinetsss->auth_id;
                }
                $posts = $posts->whereIn("client_id", $ids)
                    ->where("company_id", \Settings::company_id());
            }
            $posts = $posts->groupBy('client_id')->orderBy("client_id", $dir)->get();
            $totalFiltered = count($posts);
        }

        $data = array();

        if(!empty($posts))
        {
            $sl = count($posts);
            foreach ($posts as $key => $post)
            {
                $clinet = CatbClients::query()->where("auth_id", $post->client_id);
                $clinet = $clinet->first();
                if ($clinet) {
                    $nestedData = array();
                    if ($dir == "asc") {
                        $nestedData[] = $key + 1;
                    } else {
                        $nestedData[] = $sl;
                        $sl--;
                    }
                    $nestedData[] = $clinet->client_id . "-" . $clinet->client_name;
                    $nestedData[] = $clinet->mrp;
                    $nestedData[] = number_format($post->dis_amount, 2);
                    $payable = number_format($post->due_amount, 2);
                    $nestedData[] = $payable > 0 ? "<b style='color:red;'>" . $payable . "</b>" : $payable;
                    //$nestedData[] = number_format($post->rcv_amount, 2);
                    $action ='<div class="btn-group align-top" role="group">
                        <button id="' .$post->client_id. '" class="open btn btn-success btn-sm badge">
                    <i class="la la-money"></i></button>
                    <button id="' .$post->client_id. '" class="details btn btn-info btn-sm badge">
                    <i class="la la-eye"></i></button>
                    </div>';
                    $nestedData[] = $action;
                    $data[] = $nestedData;
                }
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        );

        echo json_encode($json_data);

    }

    public function catv_bill_history(Request $request)
    {
        $result =  Bills::query()->where("client_id",$request->id)->where("client_type",2)->get();
        if($result){
            $data=[];
            foreach($result as $row){
                $new=[];
                $new["receive_amount"]=$row->receive_amount;
                $new["discount_amount"]=$row->discount_amount;
                $new["receive_date"]=$row->receive_date;
                $new["bill_date"]=$row->bill_date;
                $new["package_name"]=$row->client_cat->package->name;
                $new["package_price"]=$row->client_cat->package->price;
                $new["payable_amount"]=$row->payable_amount;
                $new["emp_name"]=  $row->receive_by ? $row->employee->emp_name : '';
                $new["mybillid"]=$row->id;
                $data[]=$new;
            }
            return response()->json( (object) $data);
        }else{
            echo 0;
        }
    }


    public function catv_bill_details(Request $request)
    {
        $result =  Bills::query()
            ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)- sum(discount_amount)) as due_amount"))
            ->where("client_id",$request->id)
            ->where("client_type",2)
            ->groupBy("client_id")
            ->havingRaw('due_amount > 0')
            ->first();
        $client=CatbClients::where("auth_id",$request->id)->first();
        if($result){
            echo json_encode([
                "client_initial_id"=>$client->client_id,
                "client_name"=>$client->client_name,
                "payable_amount"=>$result->due_amount
            ]);
        }else{
            echo 0;
        }
    }

    public function generate_bill()
    {
       // $companies=IspResellers::where("company_id",\Settings::company_id())->get();
        $today      = date("Y-m-1");
        $previous_month = strtotime("-1 month ". $today);
        $month_year=date("F-Y",$previous_month);
        return view("back.bill.generate_catv_bill",compact('month_year'));
    }
    public  function GenerateCatvClientBill(Request $request){
        $dateString = date('ym'); //Generate a datestring.
        $branchNumber = $request->company_id; //Get the branch number somehow.
        $receiptNumber = 1;
        $client_type = 2;//catv

        $clients = CatbClients::query()
            ->where("connection_mode",1)
            ->where("company_id",1)
            ->get();

        $today      = date("Y-m-1");
        $previous_month = strtotime("-1 month ". $today);
        $this_month = date("m", $previous_month);
        $this_year  = date("Y", $previous_month);

//        $today      = date("Y-m-d");
//        $this_month = date("m");
//        $this_year  = date("Y");
        $this_month_text = date("M");
        $total_client=count($clients);
        $bill_counting=0;
        foreach ($clients as $client) {
            $bill_type = 1;//package bill/monthly bill

            $bill_exist = Bills::query()
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('company_id', $request->company_id)
                ->where('client_id', $client->auth_id)
                ->where('client_type', $client_type)//catv
                ->count();
            // dd($bill_exist);
            $bill_count = $bill_exist+1;

            $bill_id = $dateString.$bill_type.$client_type.$bill_count.$client->id;

            if($receiptNumber < 9999) {
                $receiptNumber = $receiptNumber + 1;
            }else{
                $receiptNumber = 1;
            }
//
            if($bill_exist==0){
                $catvPackages = CatvPackages::find($client->package_id);
$package_name='';
                if($catvPackages){
                    $payable=$catvPackages->price;
                    $package_name=$catvPackages->name;
                } else {
                    $payable = $client->mrp;
                }
                $new_data=array();
                $bills=new Bills();
                $bills->particular                 = 'Monthly Bill';
                $bills->company_id                 = $request->company_id;
                $bills->client_id                  = $client->auth_id;
                $bills->client_initial_id          = $client->client_id;
                $bills->bill_id                    = $bill_id;
                $bills->bill_date                  = $today;
                $bills->bill_month                 = $this_month;
                $bills->bill_year                  = $this_year;
                $bills->bill_type                  = $bill_type;
                $bills->client_type               = $client_type;//catv
                $bills->bill_approve               = 1;
                $bills->bill_status                = 0;
                $bills->package_title              = $package_name;
                $bills->package_id                 = $client->package_id;
                $bills->package_amount             = $payable;
                $bills->payable_amount             = $payable;
                $billSaveResult=$bills->save();
                if($billSaveResult){
                    $history=new IspBillHistorys();
                    $history->particular        = "Monthly bill";
                    $history->company_id        = $request->company_id;
                    $history->client_id         = $bills->client_id;
                    $history->bill_id           = $bills->bill_id;
                    $history->bill_year         = $bills->bill_year;
                    $history->bill_month        = $bills->bill_month;
                    $history->bill_amount       = $bills->payable_amount;
                    $history->client_type       = $client_type;//catv
                    $history->receive_amount    = 0;
                    $historySaveReulst=$history->save();

                    if($historySaveReulst){
                        $bill_counting++;
                        if($client->payment_alert_sms==1 && $request->payment_confirm_sms==1){
                            $dueBill = Bills::query()->select(DB::raw('(SUM(payable_amount)-SUM(receive_amount)) AS payable'))
                                ->where("client_id",$client->auth_id)
                                ->where("client_type",$client_type)
                                ->get();
                            if(count($dueBill)>0){
                                $client_due=$dueBill[0]->payable;
                            }
                            else{
                                $client_due=0;
                            }

                            $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
                            $name = explode(" ",$client->client_name)[0];
                            $sms_text = "Dear ".$name ." (".$client->client_id."), the bill for the month of ".$this_month_text." has been generated as ".$bills->payable_amount."tk. Unless payment is not confirmed the link will automatically expire on ".$this->ordinal_suffix($client->payment_dateline)." ".$this_month_text.". Total due ".($bills->payable_amount+$client_due)."tk. Pay your bill today (Bkash/Cash: 01993678660) Call: 01841918091";
                            //echo $sms_text = "Dear ".$client->client_name." (".$client->client_id."), your last month bill ".$new_data["payable_amount"]."tk. has been due.".$client_due;
                            $cell_no        = $client->cell_no;
                            $sms_count        = round(strlen($sms_text)/160)==0?1:round(strlen($sms_text)/160);
                            $sms_type="english";
                            if(strlen($cell_no)==13){
                                $sms_status="Pending";
                                $is_retry=1;
                            }else{
                                $sms_status="Receiver Error";
                                $is_retry=0;
                            }
                            $created_at = date("Y-m-d H:i");
                            $smsData=new SmsHistory();
                            $smsData->company_id=$request->company_id;
                            $smsData->sms_receiver=$cell_no;
                            $smsData->sms_sender="Administrator";
                            $smsData->sms_count=$sms_count;
                            $smsData->sms_type=$sms_type;
                            $smsData->sms_text=$sms_text;
                            $smsData->sms_api=$sms_api;
                            $smsData->is_retry=$is_retry;
                            $smsData->sms_status=$sms_status;
                            $smsData->sms_schedule_time=$created_at;
                            $smsData->sent_time=$created_at;
                            $smsData->save();
                        }
                    }
                }
            }
        }
        if($total_client==$bill_counting){
            return response()->json(['status'=>'success']);
        }
    }

}
