<?php

namespace App\Http\Controllers\Bills;

use App\Http\Controllers\Controller;
use App\Models\IspResellers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\PaymentMethods;
use App\Models\IspResellerBills;
use App\Models\IspBillHistorys;
use App\Models\Packages;
use Illuminate\Foundation\Auth\User;


class ResellerBillController extends Controller
{
    protected $employees = "employees";
    protected $clients = "isp_resellers";
    protected $packages = "packages";
    protected $zones = "zones";
    protected $nodes = "nodes";
    protected $bills = "isp_reseller_bills";

    public function index()
    {

        $payments =PaymentMethods::query()->where(["company_id"=>auth()->user()->company_id])->get();
        if(in_array(auth()->user()->ref_role_id,[1,2])) {
            $employees = User::select("auth_id", "emp_name")
                ->leftJoin($this->employees, "auth_id", "=", "users.id")
                ->where("users.id", "<>", "1")
                ->where(["users.company_id" => auth()->user()->company_id, "is_admin" => 1])
                ->get();
        }
        else{
            $employees = [];
        }
        return view("back.bill.reseller_bill", compact("employees","payments"));
    }
    public function generate_bill()
    {
        $packages=Packages::all();
        $clients =Clients::all();
        return view("back.bill.generate_isp",compact("clients","packages"));
    }
    public  function saveGenerateBill(Request $request){
        $dateString = date('Ymd'); //Generate a datestring.
        $branchNumber = auth()->user()->company_id; //Get the branch number somehow.
        $receiptNumber = 1;  //You will query the last receipt in your database
//and get the last $receiptNumber for that branch and add 1 to it.;



        $start = Carbon::now()->startOfMonth()->toDateString()." 00:00:00";
        $end = Carbon::now()->endOfMonth()->toDateString()." 23:00:00";

        $clients = DB::table($this->clients)
            // ->where("connection_mode",1)
            ->where("id",$request->client_id)
            ->get();
        $packages = DB::table($this->packages)
            // ->where("connection_mode",1)
            ->where("id",$request->package_id)
            ->first();
//dd($clients);
//        $today      = date("Y-m-d");
//        $this_month = date("m");
//        $this_year  = date("Y");

        $today      = date("Y-m-d", strtotime(str_replace("/","-",$request->bill_date)));
        $this_month = date("m",strtotime($today));
        $this_year  = date("Y",strtotime($today));
        $data = $sms_data = $historyData = array();
        foreach ($clients as $client) {
            $bill_type = 1;//package bill/monthly bill

            $bill_exist = DB::table($this->bills)
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('client_id', $request->client_id)
                ->count();
            // dd($bill_exist);
            $bill_count = $bill_exist+1;

            //$bill_id = $this_year.$this_month.$bill_type.$bill_count.$client->cl_id;

            if($receiptNumber < 9999) {

                $receiptNumber = $receiptNumber + 1;

            }else{
                $receiptNumber = 1;
            }
            $bill_id=$dateString.$branchNumber.$receiptNumber;

            if($bill_exist==0){
                $new_data=array();
                $new_data["company_id"]                  = auth()->user()->company_id;
                $new_data["client_id"]                  = $client->id;
                $new_data["client_initial_id"]          = $client->client_id;
                $new_data["bill_id"]                    = $bill_id;
                $new_data["bill_date"]                  = $today;
                $new_data["bill_month"]                 = $this_month;
                $new_data["bill_year"]                  = $this_year;
                $new_data["bill_type"]                  = $bill_type;
                $new_data["bill_status"]                = 0;
                $new_data["package_id"]                 = $packages->id;
                $new_data["payable_amount"]             = $packages->package_price - $client->permanent_discount;
                $new_data["permanent_discount_amount"]  = $client->permanent_discount;
                $data[]= $new_data;

                $history=array();
                $history["particular"]= "Monthly bill";
                $history["company_id"]                  = auth()->user()->company_id;
                $history["client_id"]= $new_data["client_id"];
                $history["bill_id"]= $new_data["bill_id"];
                $history["bill_year"]= $new_data["bill_year"];
                $history["bill_month"]= $new_data["bill_month"];
                $history["bill_amount"]= $new_data["payable_amount"];
                $history["receive_amount"]= 0;
                $history["created_at"]= date("Y-m-d H:i");
                $history["updated_at"]= date("Y-m-d H:i");
                $historyData[]=$history;

//                exit();
                if($client->payment_alert_sms==1){
                    $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
                    $sms_text = "Dear ".$client->client_name." (".$client->client_id."), your last month bill ".$new_data["payable_amount"]."tk. has been due.";
                    $cell_no        = $client->cell_no;
                    $sms_count        = round(strlen($sms_text)/160)==0?1:round(strlen($sms_text)/160);
                    $sms_type="english";
                    $sms_status="Pending";
                    $created_at = date("Y-m-d H:i");
                    $sms_data[] = [
                        "sms_receiver"=>$cell_no,
                        "sms_sender"=>"Administrator",
                        "sms_count"=>$sms_count,
                        "sms_type"=>$sms_type,
                        "sms_text"=>$sms_text,
                        "sms_api"=>$sms_api,
                        "sms_status"=>$sms_status,
                        "sms_schedule_time"=>$created_at,
                        "sent_time"=>$created_at,
                        "created_at"=>$created_at
                    ];

                }

                //print_r ($sms_data);
            }
        }
        if($data){

            IF($historyData){
                IspBillHistorys::query()->insert($historyData);
            }
            $query = DB::table($this->bills)->insert($data);
            if($query){
                //  DB::table($this->sms_history)->insert($sms_data);
                echo 1;
            }else{
                echo 0;
            }
        } else {
            echo 101;
        }

    }

    public function createBill(Request $request)
    {
        $payable = $request->payable;
        $bills = IspBillHistorys::query()
            ->where(
                [
                    "company_id"     => auth()->user()->company_id,
                    "client_id"     => $request->id,
                    "bill_status"   => 0,
                    "bill_type"   => 'reseller'
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

        $result = IspResellerBills::query()->insert(
            [
                "company_id"        => auth()->user()->company_id,
                "payment_method_id"        => $request->payment_method_id,
                "bill_id"           => uniqid(),
                "bill_status"       => 1,
                "client_id"         => $request->id,
                "receive_amount"    => $request->receive_amount,
                "discount_amount"   => $request->discount_amount,
                "note"              => $request->note,
                "receive_date"      => date("Y-m-d", strtotime(str_replace("/","-",$request->receive_date))),
                "bill_month"        => date("m", strtotime(str_replace("/","-",$request->receive_date))),
                "bill_year"         => date("Y", strtotime(str_replace("/","-",$request->receive_date))),
                "receive_by"        => $request->collected_by ,
                "updated_at"        => date("Y-m-d H:i:s")
            ]
        );
//
        if($result){
            //create welcome sms
            if(isset($request->payment_confirm_sms)){
                if($request->payment_confirm_sms==1){
                    $clients = IspResellers::find( $request->id);
                    $bal=$request->receive_amount+$request->discount_amount;
                    $due=$payable-$bal;
                    // $sms_text = "Dear ".$clients->client_name.", your current month bill has been paid.";
                    // $sms_text = "Hello, Greetings from IMAXQ . We've successfully received your payment ".$total_amount."tk,still due is 2000tk ,date line is 15 august ,please pay your due 01993678660(bkash) Hotline:01841918091";
                    $sms_text = "Hello, Greetings from IMAXQ . We've successfully received your payment ".$bal."tk.";
                    if($due){
                        $sms_text.="\nStill due is ".$due."tk ,date line is ". date("d, F",strtotime(date("Y-m-d")."+15days "));
                    }
                    $sms_text.="\nHotline:01841918091";

                    $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
                    $sms_data=array(
                        "to"            =>  $clients->personal_contact,
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
//        }else{
//            echo 101;
//        }


    }

    //all due bill
    public function dueBill(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'reseller_id'
        );

        $totalData = count(IspResellerBills::query()
            ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as due_amount"))
            ->where("company_id",auth()->user()->company_id)
            ->groupBy("client_id")
            ->havingRaw('due_amount > 0')
            ->get());

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {

            $posts = IspResellerBills::query()
                ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as due_amount,client_id"))
                ->where("company_id",auth()->user()->company_id)
                ->groupBy("client_id")
                ->orderBy("client_id",$dir)
                ->havingRaw('due_amount > 0')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $clients = IspResellers::query()
                ->select("id")
                ->where('reseller_id','=',"$search")
                ->where("company_id",auth()->user()->company_id)
                ->orWhere('reseller_name', 'LIKE',"%{$search}%")
                ->where(["connection_mode"=>1])
                ->orderBy($order,$dir)
                ->get();

            $posts =   IspResellerBills::query()
                ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as due_amount,client_id"));


            if(count($clients)){
                $ids=[];
                foreach ($clients as $clientss) {
                    $ids[]=$clientss->id;
                }
                $posts =$posts->whereIn("client_id",$ids)->where("company_id",auth()->user()->company_id);
            }
            $posts =$posts->groupBy("client_id")
                ->orderBy("client_id",$dir)
                ->havingRaw('due_amount > 0')
                ->get();

            $totalFiltered =  count($posts);
        }
        $data = array();
        if(!empty($posts))
        {
            $sl=count($posts);
            foreach ($posts as $key=>$post)
            {
                if ($post->client->reseller_type == "Mac"){
                    $client_package = $post->client->package->package_name . "<br><span class='taka'>&#2547;.</span>" . $post->client->package->package_price;
                }else{
                    $bandwidth_details= json_decode($post->client->bandwidth_details);
                    $bandwidth_total=[];
                    foreach($bandwidth_details as $row){
                        $bandwidth_total[]=$row->qty*$row->price;
                    }
                    $client_package="Tk".array_sum($bandwidth_total);
                }
                $nestedData = array();

                if($dir=="asc"){
                    $nestedData[] = $key+1;
                }else{
                    $nestedData[] = $sl;
                    $sl--;
                }
                $nestedData[]   = $post->client->reseller_id."<br>".$post->client->reseller_name;
                $nestedData[]   = $post->client->personal_contact;
                $nestedData[]   = $client_package;
                $nestedData[]   = number_format( $post->client->permanent_discount,2);
                $payable        = number_format( $post->due_amount,2);

                $nestedData[] = $payable>0 ? "<b style='color:red;'>".$payable."</b>": $payable;
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                     <button id="' .$post->client_id. '" class="open btn btn-primary btn-sm badge">
                                    <i class="la la-money"></i></button>
                                    <button id="'.$post->client_id.'" title="Send SMS" class="dueSMS btn btn-warning btn-sm badge">
                                    <i class="la la-envelope"></i></button>
                                    <button id="'.$post->client_id.'" title="Commitment Date" class="commitDate btn btn-success btn-sm badge">
                                    <i class="la la-calendar"></i></button>
                                    <button id="'.$post->client_id.'" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
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

    //today bill collection
    public function TodayCollectionList(Request $request)
    {
        $today = date("Y-m-d");

        $columns = [
            1 =>'id',
            2 =>'reseller_name',
        ];

        $totalData = IspResellerBills::query()
            ->select(
                DB::raw("sum(receive_amount) as rcv_amount"),
                DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                "client_id"
            )
            ->where("receive_date",$today)
            ->groupBy('client_id')
            ->count();


        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = IspResellerBills::query()
                ->select(
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                    "client_id"
                )
                ->where("receive_date",$today)
                ->groupBy('client_id')
                //->havingRaw("rcv_amount>0")
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $clinets = IspResellers::query()
                ->select("id")
                ->orWhere('reseller_id','=',"$search")
                ->orWhere('reseller_name', 'like',"%{$search}%")
                ->orderBy($order,$dir)
                ->get();

            $posts = IspResellerBills::query()
                ->select(
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                    "client_id"
                )
                //->where("receive_date",date("Y-m-d"))
            ;

            if(count($clinets)>0){
                $ids=[];
                foreach ($clinets as $clinetsss) {
                    $ids[]=$clinetsss->id;
                }
                $posts =  $posts->whereIn("client_id",$ids);
            }

            $posts =  $posts->groupBy('client_id')
                //->havingRaw("rcv_amount>0")
                ->get();

            $totalFiltered =  count($posts);
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $clinet = IspResellers::find($post->client_id);
                if ($clinet->reseller_type == "Mac"){
                    $pack = Packages::find($clinet->package_id);
                    if ($pack) {
                        $client_package = $pack->package_name . "<br><span class='taka'>&#2547;.</span>" . $pack->package_price;
                    }
                }else{
                    $bandwidth_details= json_decode($clinet->bandwidth_details);
                    $bandwidth_total=[];
                    foreach($bandwidth_details as $row){
                        $bandwidth_total[]=$row->qty*$row->price;
                    }
                    $client_package="Tk".array_sum($bandwidth_total);
                }
                $nestedData = array();

                $nestedData[] = $post->client_id;
                $nestedData[] = $clinet->reseller_id."<br>".$clinet->reseller_name;
                $nestedData[] = $clinet->personal_contact;
                $nestedData[] = $client_package;
                $nestedData[] = number_format( $post->dis_amount,2);
                $nestedData[] = number_format( $post->rcv_amount,2);
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                    <button id="'.$post->client_id.'" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
                                </div>';

                $data[] = $nestedData;
            }
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        echo json_encode($json_data);

    }

    //all collection bill
    public function AllCollectedBill(Request $request)
    {

        $columns = [
            0 =>'id',
            1 =>'reseller_name',
        ];


        $date_from = date("Y-m-d",strtotime($request->date_from));
        $date_to = date("Y-m-d",strtotime($request->date_to));

        $totalData = IspResellerBills::query()
            ->select(
                DB::raw("sum(receive_amount) as rcv_amount"),
                DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                "client_id"
            )
            // ->whereBetween("receive_date",[$date_from,$date_to])
            ->groupBy('client_id')
            ->count();

        $totalFiltered = $totalData;


        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = IspResellerBills::query()
                ->select(
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                    "client_id"
                )
                // ->whereBetween("receive_date",[$date_from,$date_to])
                ->groupBy('client_id')
                //->havingRaw("rcv_amount>0")
                ->get();
        }
        else {
            $search = $request->input('search.value');


            $clinets = IspResellers::query()
                ->select("id")
                ->orWhere('reseller_id','=',"$search")
                ->orWhere('reseller_name', 'like',"%{$search}%")
                ->orderBy($order,$dir)
                ->get();

            $posts = IspResellerBills::query()
                ->select(
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                    "client_id"
                );
            // ->whereBetween("receive_date",[$date_from,$date_to]);

            if(count($clinets)>0){
                $ids=[];
                foreach ($clinets as $clinetsss) {
                    $ids[]=$clinetsss->id;
                }
                $posts =  $posts->whereIn("client_id",$ids);
            }

            $posts =  $posts->groupBy('client_id')
                //->havingRaw("rcv_amount>0")
                ->get();

            $totalFiltered =  count($posts);

        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                if ($post->client->reseller_type == "Mac"){
                    $pack = Packages::find($post->client->package_id);
                    if ($pack) {
                        $client_package = $pack->package_name . "<br><span class='taka'>&#2547;.</span>" . $pack->package_price;
                    }
                }else{
                    $bandwidth_details= json_decode($post->client->bandwidth_details);
                    $bandwidth_total=[];
                    foreach($bandwidth_details as $row){
                        $bandwidth_total[]=$row->qty*$row->price;
                    }
                    $client_package="Tk".array_sum($bandwidth_total);
                }

                $nestedData = array();

                $nestedData[] = $post->client_id;
                $nestedData[] = $post->client->reseller_id."<br>".$post->client->reseller_name;
                $nestedData[] = $post->client->personal_contact;
                $nestedData[] = $client_package;
                $nestedData[] = number_format( $post->dis_amount,2);
                $nestedData[] = number_format( $post->rcv_amount,2);
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                    <button id="'.$post->client_id.'" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
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

    //client all bill due/paid
    public function reseller_all_bill(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'reseller_name',
        );

        $date_from = date("Y-m-d",strtotime($request->date_from));
        $date_to = date("Y-m-d",strtotime($request->date_to));

        $totalData = IspResellerBills::query()
            ->select(
                DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                DB::raw("sum(receive_amount) as rcv_amount"),
                DB::raw("sum(discount_amount) as dis_amount"),
                "client_id"
            )->where("company_id",auth()->user()->company_id)
            ->groupBy('client_id')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = IspResellerBills::query()
                ->select(
                    DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("sum(discount_amount) as dis_amount"),
                    "client_id"
                )->where("company_id",auth()->user()->company_id)
                ->groupBy('client_id')
                ->orderBy("id",$dir);
            $posts =  $posts->get();
        } else {
            $search = $request->input('search.value');
            $clinets = IspResellers::query()
                ->select("id")
                ->orWhere('reseller_id',"like","%{$search}%")
                ->orWhere('reseller_name', 'like',"%{$search}%")
                ->get();


            $posts =   IspResellerBills::query()
                ->select(
                    DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    "client_id"
                );
            if(count($clinets)>0){
                $ids=[];
                foreach ($clinets as $clinetsss) {
                    $ids[]=$clinetsss->id;
                }
                $posts =  $posts->whereIn("client_id",$ids)
                    ->where("company_id",auth()->user()->company_id);
            }
            $posts =$posts->groupBy('client_id')->orderBy("client_id",$dir)->get();
            $totalFiltered =  count($posts);
        }

        $data = array();
        if(!empty($posts))
        {
            $sl=count($posts);
            foreach ($posts as $key=>$post)
            {
                if($post->client) {
                    if ($post->client->reseller_type == "Mac"){
                        $client_package = $post->client->package->package_name . "<br><span class='taka'>&#2547;.</span>" . $post->client->package->package_price;

                    }else{
                       $bandwidth_details= json_decode($post->client->bandwidth_details);
                        $bandwidth_total=[];
                        foreach($bandwidth_details as $row){
                            $bandwidth_total[]=$row->qty*$row->price;
                        }
                        $client_package="Tk ".array_sum($bandwidth_total);
                    }

                    $nestedData = array();
                    if($dir=="asc"){
                        $nestedData[] = $key+1;
                    }else{
                        $nestedData[] = $sl;
                        $sl--;
                    }

                    $nestedData[] = $post->client->reseller_id."<br>".$post->client->reseller_name;
                    $nestedData[] = $post->client->personal_contact;
                    //$nestedData[] = ($clinet->termination_date !='0000-00-00') ?  date("d M, Y", strtotime($clinet->termination_date)) :'';
                    $nestedData[] = $client_package;
                    //$nestedData[] = number_format( $clinet->permanent_discount,2);
                    $payable      = number_format( $post->due_amount,2);
                    $nestedData[] = $payable>0 ? "<b style='color:red;'>".$payable."</b>": $payable ;
                    $nestedData[] =  number_format($post->rcv_amount,2);
                    $nestedData[] = '<div class="btn-group align-top" role="group">
                                         <button id="' .$post->client_id. '" title="Collect bill" class="open btn btn-primary btn-sm badge">
                                        <i class="la la-money"></i></button>
                                        <button id="'.$post->client_id.'" title="Send SMS" class="dueSMS btn btn-warning btn-sm badge">
                                        <i class="la la-envelope"></i></button>
                                        <button id="'.$post->client_id.'" title="Commitment Date" class="commitDate btn btn-success btn-sm badge">
                                        <i class="la la-calendar"></i></button>
                                        <button id="'.$post->client_id.'" title="View" class="viewBill btn btn-info btn-sm badge">
                                        <i class="la la-eye"></i></button>
                                    </div>';

                    $data[] = $nestedData;
                }
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

    public function BillInfo(Request $request)
    {
        $result =  IspResellerBills::query()
            ->select(DB::raw("client_id,(sum(payable_amount) - sum(receive_amount)- sum(discount_amount)) as due_amount"))
            ->where("client_id",$request->id)
            ->groupBy("client_id")
           // ->havingRaw('due_amount > 0')
            ->first();

        if($result){
            echo json_encode([
                "client_initial_id"=>$result->client->reseller_id,
                "client_name"=>$result->client->reseller_name,
                "payable_amount"=>$result->due_amount
            ]);
        }else{
            echo 0;
        }
    }

    public function isp_reseller_bill_history(Request $request)
    {
        $result =  IspResellerBills::query()
            ->leftJoin($this->employees,$this->employees.".id","=",$this->bills.".receive_by")
            ->where("isp_reseller_bills.client_id",$request->id)->get();
        if($result){
            $data=[];


            foreach ($result as $row) {
                if ($row->client->reseller_type == "Mac"){

                    $client_package = $row->client->package->package_name . "<br><span class='taka'>&#2547;.</span>" . $row->client->package->package_price;

                }else{
                    $bandwidth_details= json_decode($row->client->bandwidth_details);
                    $bandwidth_total=[];
                    foreach($bandwidth_details as $rows){
                        $bandwidth_total[]=$rows->qty*$rows->price;
                    }
                    $client_package="Tk ".array_sum($bandwidth_total);
                }

                $data2["bill_id"]=$row->bill_id;
                $data2["permanent_discount_amount"]=$row->permanent_discount_amount;
                $data2["discount_amount"]=$row->discount_amount;
                $data2["payable_amount"]=$row->payable_amount;
                $data2["receive_amount"]=$row->receive_amount;
                $data2["package"]=$client_package;
                $data2["emp_name"]=$row->emp_name;
                $data2["receive_date"]=$row->receive_date;
                $data[]=$data2;
            }

            echo json_encode($data);
        }else{
            echo 0;
        }
    }

    public function isp_reseller_due_sms(Request $request)
    {
        $dueBills = IspBillHistorys::query()
            ->select(DB::raw("SUM(bill_amount - receive_amount) AS bill, bill_month, bill_year"))
            ->where(
                [
                    "client_id"=>  trim($request->id),
                    "bill_status"=>0,
                    "bill_type"   => 'reseller'
                ]
            )
            ->groupBy(["bill_month","bill_year"])
            ->get();

        $clients =IspResellers::where('auth_id',trim($request->id))->first();
        $months=[1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec"];
        $data=[];
        if(count($dueBills)>0){
            foreach ($dueBills as $bill) {

                $data[]= $months[$bill->bill_month] ." ". $bill->bill_year ."-".($bill->bill)."Tk";
            }
            echo json_encode(["status"=>true,"sms"=>"Dear customer pls pay your dues of ".implode(",",$data),"sent_to"=>$clients->personal_contact,"main"=>$dueBills]);
        }else {
            echo json_encode(["status"=>false]);
        }

    }

    public function isp_reseller_due_sms_save(Request $request)
    {
        $sms_data=array(
            "to"            =>  $request->sent_to,
            "sms_text"      =>  $request->sms_text,
            "sms_from"      =>  "isp",
            "sms_sender"    =>  Auth::user()->name,
            "sms_type"      =>  "english",
            "sms_api"       =>  null,
            "schedule_time" =>  date("Y-m-d H:i")
        );
        app('App\Http\Controllers\SMS\SMSController')->master_save_sms($sms_data);
        echo 1;
    }
    public function isp_commitment_date_update(Request $request)
    {
        $update = IspResellers::query()->whereId($request->client_id)->update(["termination_date"=>$request->commitment_date]);
        if($update){

            $smsText ="Dear ".$request->name.", your commitment date is ".(date("d F,Y",strtotime($request->commitment_date))).". Please contact & pay due bill As soon as possible.01993678660(bkash/nagad) reff:ID/Name.\nHotline:01841918091";

            $sms_data=array(
                "to"            =>  $request->mobile,
                "sms_text"      =>  $smsText,
                "sms_from"      =>  "isp",
                "sms_sender"    =>  Auth::user()->name,
                "sms_type"      =>  "english",
                "sms_api"       =>  null,
                "schedule_time" =>  date("Y-m-d H:i")
            );
            app('App\Http\Controllers\SMS\SMSController')->master_save_sms($sms_data);
            echo 1;
        }else{
            echo 0;
        }
    }

}
