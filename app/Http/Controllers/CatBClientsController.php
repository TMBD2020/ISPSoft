<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplates;
use App\Models\CatbClients;
use App\Models\Companies;
use App\Models\Microtiks;
use App\Models\Packages;
use App\Models\CatbCableType;
use App\Models\PaymentMethods;
use App\Models\CatvPackages;
use App\Models\Zones;
use App\Models\Bills;
use App\Models\BalanceTransactions;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\User;
use App\Imports\CATVClientImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use DB;
use Auth;

class CatbClientsController extends Controller
{

    protected $employees = "employees";
    protected $id_types = "id_types";
    protected $id_prefixs = "id_prefixs";


    public function index()
    {
        $total_client       = CatbClients::all()->count();
        $active_client      = CatbClients::all()->where("connection_mode",1)->count();
        $inactive_client    = CatbClients::all()->where("connection_mode",0)->count();
        $locked_client      = CatbClients::all()->where("connection_mode",2)->count();
        $catv_packages      = CatvPackages::all();
        $networks           = Microtiks::all();
        $packages           = Packages::all();
        $zones              = Zones::query()->where("zone_type",2)->get();
        $cable_types        = CatbCableType::all();
        $payment_method     = PaymentMethods::all();
        $id_type            = DB::table($this->id_types)->where('id', 4)->first()->id;
        $id_prefixs         = DB::table($this->id_prefixs)->where('ref_id_type_name', $id_type)->get();
        $sms_templates= SmsTemplates::all();

        $employees = DB::table($this->employees)->where("is_resign","0")->get();
        return view("back.clients.catv",
            compact(
                "sms_templates",
                "employees",
                "id_prefixs",
                "total_client",
                "active_client",
                "inactive_client",
                "locked_client",
                "networks",
                "cable_types",
                "zones",
                "payment_method",
                "catv_packages",
                "packages"
        ));
    }

    public function store(Request $request)
    {
        if($request->action==1){

            $clients = New CatbClients();

            $userCount = User::query()->where("email",$request->client_username)->get();
            if(count($userCount)>0){
                $user = $userCount[0];
                User::query()->whereId($user->id)->update(["name"=>$request->client_name]);
            } else {
                $user = New User();
                $user->name         = $request->client_name;
                $user->email        = $request->client_username;
                $user->user_id      = $request->client_username;
                $user->password     = bcrypt($request->password);
                $user->user_type    = "catb_client";
                $user->save();
            }

            if($user){
                $clients->auth_id           = $user->id;
                $clients->client_id       = $user->email;
                $clients->client_name       = $request->client_name;
                $clients->prefix_id      = $request->prefix_id;
                $clients->zone_id      = $request->zone_id;
                $clients->sub_zone_id      = $request->sub_zone_id;
                $clients->home_card_no      = $request->card_no;
                $clients->cell_no           = $request->cell_no;
                $clients->payment_dateline  = $request->payment_dateline;
                $clients->join_date         = date('Y-m-d',strtotime(str_replace("/","-",$request->join_date)));
                $clients->email             = $request->email;
                $clients->nid               = $request->nid;
                $clients->occupation        = $request->occupation;
                $clients->address           = $request->address;
                $clients->thana             = $request->thana;
                $clients->payment_id        = $request->payment_id;
                $clients->alter_cell_no_1   = $request->alter_cell_no_1;
                $clients->otc               = $request->otc;
                $clients->package_id               = $request->package_id;
                $clients->mrp               = $request->mrp;
                $clients->payment_alert_sms = $request->payment_alert_sms;
                $clients->payment_conformation_sms  = $request->payment_conformation_sms;
                $clients->cable_id          = $request->cable_id;
                $clients->required_cable    = $request->required_cable;
                $clients->otc              = $request->otc;
                $clients->mrp              = $request->mrp;
                $clients->note              = $request->note;
                $clients->connection_mode   = 1;
                if ($request->hasFile('picture')) {
                    $file = $request->file('picture');
                    $picture_name = $clients->auth_id . "." . $file->guessClientExtension();
                    $clients->picture = 'catv_clients/' . $picture_name;
                    Image::make($request->photo)->resize(150, 150)->save($clients->picture);
                }
                $clients->save();

                if ($clients) {
                    $cl_id = $clients->id;
                    //create bill
                    $today = date("Y-m-d");
                    if($request->receive_date){
                        $receive_date = date("Y-m-d", strtotime(str_replace("/","-",$request->receive_date)));
                        $bill["receive_date"] = $receive_date;
                    } else {
                        $bill["receive_date"] = null;
                        $receive_date = $today;
                    }

                    $this_year = date("Y", strtotime($receive_date));
                    $this_month = date("m", strtotime($receive_date));
                    $bill_type = 1;
                    $bill_count = 1;
                    $bill_id = $this_year . $this_month . $bill_type.(2). $bill_count . $cl_id;
                    $bill["client_id"] = $cl_id;
                    $bill["client_initial_id"] = $clients->client_id;
                    $bill["bill_id"] = $bill_id;
                    $bill["bill_date"] = date("Y-m-d");
                    $bill["bill_month"] = $this_month;
                    $bill["bill_year"] = $this_year;
                    $bill["bill_type"] = $bill_type;
                    $bill["bill_status"] = 1;
                    $bill["client_type"] = 2;
                    $bill["package_id"] = $request->package_id;
                    $bill["package_amount"] = $request->mrp;
                    $bill["previous_amount"] = $request->previous_due;
                    $bill["payable_amount"] = $request->payable_amount;
                    $bill["connection_charge"] = $request->otc;
                    $bill["discount_amount"] = $request->discount;
                    $bill["receive_amount"] = $request->receive_amount;
                    $bill["receive_by"] = Auth::user()->id;

                    $insert_bill = Bills::query()->insert($bill);
                    //end bill creation








//                    if($insert_bill){
//                        //create welcome sms
//                        if(isset($request->welcome_sms)){
//                            if($request->welcome_sms==1){
//                                $sms_text = "Dear ".$data["client_name"].", thanks to being with us.
//                            Your username: ".$data["client_id"]." and Password: ".$request->client_password;
//
//                                $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
//                                $sms_data=array(
//                                    "to"            =>  $data["cell_no"],
//                                    "sms_text"      =>  $sms_text,
//                                    "sms_from"      =>  "Welcome SMS",
//                                    "sms_sender"    =>  Auth::user()->name,
//                                    "sms_type"      =>  "english",
//                                    "sms_api"       =>  $sms_api,
//                                    "schedule_time" =>  $data["created_at"]
//                                );
//                                app('App\Http\Controllers\SMS\SMSController')->master_save_sms($sms_data);
//                            }
//                        }
//                    }
                }

                if($clients){
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {
           return $this->update($request);
        }
    }

    public function edit(Request $request)
    {
        $clients    =   CatbClients::find($request->id);
        echo json_encode($clients);
    }


    public function update(Request $request)
    {
        $data = [
            "client_name"          => $request->client_name,
            "zone_id"         => $request->zone_id,
            "sub_zone_id"         => $request->sub_zone_id,
            "payment_dateline"         => $request->payment_dateline,
            "join_date"            =>   date('Y-m-d',strtotime(str_replace("/","-",$request->join_date))),
            "billing_date"         => $request->billing_date,
            "home_card_no"         => $request->card_no,
            "cell_no" => $request->cell_no,
            "payment_id"        => $request->payment_id,
            "payment_alert_sms"        => $request->payment_alert_sms,
            "payment_conformation_sms"        => $request->payment_conformation_sms,
            "alter_cell_no_1"        => $request->alter_cell_no_1,
            "address"        => $request->address,
            "package_id"        => $request->package_id,
            "thana"        => $request->thana,
            "occupation"        => $request->occupation,
            "email"        => $request->email,
            "nid"        => $request->nid,
            "connection_mode"        => $request->connection_mode,
            "cable_id"        => $request->cable_id,
            "required_cable"        => $request->required_cable,
            "note"        => $request->note,
        ];
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $picture_name   = $request->id  . "." . $file->guessClientExtension();
            $photo          = 'catv_clients/' . $picture_name;
            Image::make($request->photo)->resize(150, 150)->save($photo);
            $data = array_merge($data,["picture"=>$photo]);
        }

        $result = CatbClients::query()->where("id",$request->id)->update($data);



        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function clientList(Request $request)
    {
        $columns = array(
            0 => 'catb_clients.id',
            1 => 'client_id',
            2 => 'home_card_no',
            3 => 'zone_id',
            4 => 'sub_zone_id',
            5 => 'payment_dateline',
        );

        $status = $request->input('filter.status');
        $zone = $request->input('filter.zone');
        $where =array();

        $totalData = CatbClients::query();

             if($zone!='all'){
                 $totalData = $totalData->where(["zone_id"=>$zone]);
             }
        if($status!='all'){
            $totalData = $totalData->where(["connection_mode"=>$status]);
        }
            $totalData=$totalData->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = CatbClients::query()
                ->select("catb_clients.*","zones.zone_name_en", "catb_clients.id as client_pk_id")
                ->leftJoin("zones", "catb_clients.zone_id", "=", "zones.id");


            if($zone!='all'){
                $posts = $posts->where(["zone_id"=>$zone]);
            }
            if($status!='all'){
                $posts = $posts->where(["connection_mode"=>$status]);
            }
            $posts = $posts->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = CatbClients::query()
                ->select("catb_clients.*","zones.zone_name_en", "catb_clients.id as client_pk_id")
                ->leftJoin("zones", "catb_clients.zone_id", "=", "zones.id")
                ->where('id', '=', "{$search}")
                ->orWhere('client_name', 'LIKE', "%{$search}%");

            if($zone!='all'){
                $posts = $posts->where(["zone_id"=>$zone]);
            }
            if($status!='all'){
                $posts = $posts->where(["connection_mode"=>$status]);
            }
            $posts = $posts
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = count($posts);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData = array();

                $nestedData[] = $post->client_pk_id;
                $nestedData[] = $post->client_id . "<br>" . $post->client_name . "<br>" . $post->cell_no;
                $nestedData[] = $post->home_card_no;
                $nestedData[] = $post->zone_name_en . "/" . $post->address;
                $nestedData[] = $post->mrp;
                $nestedData[] = date("d/m/y", strtotime($post->join_date));
                $nestedData[] = $post->payment_dateline;
                /*
                if($post->connection_mode==1){
                    $connection_mode = "Active";
                }elseif($post->connection_mode==0){
                    $connection_mode = "De-active";
                }elseif($post->connection_mode==2){
                    $connection_mode = "Locked";
                }*/
                $nestedData[] = $post->connection_mode;
                $data[] = $nestedData;
            }
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ];

        echo json_encode($json_data);

    }


    public function catv_client_import(Request $request)
    {
        Excel::import(new CATVClientImport, $request->file('file'));
            //->store('temp'));
        return back()->with("msg","Import Successfully");
    }

    public function destroy(Request $request)
    {
        $clients = CatbClients::find($request->id);
        $user = User::find($clients->auth_id);

        if($clients->delete()){
            $user->delete();
            echo 1;
        } else {
            echo 0;
        }
    }


    public function last_client_id(Request $request)
    {
        $id_prefix = $request->prefix;

        if ($id_prefix) {
            $prefix = DB::table($this->id_prefixs)->whereid( $id_prefix)->first();
            $initial_id_digit = $prefix->initial_id_digit;
            $prefix_name = $prefix->id_prefix_name;

            $total_client = CatbClients::query()->where("prefix_id",$id_prefix)->count();

            if($total_client>0) {
                $total_client+=1;
            }
            $new_client_id = $initial_id_digit + $total_client;
            $new_client_id = $prefix_name . $new_client_id;

            echo $new_client_id;
        } else {
            echo 0;
        }
    }


    public function clientCount(Request $request)
    {
        $active = CatbClients::query()->where([
            "connection_mode"=> 1
        ])->count();

        $inactive = CatbClients::query()->where([
            "connection_mode"=> 1
        ])->count();

        echo json_encode(
            [
                "active" => $active,
                "inactive" => $inactive,
            ]
        );
    }


}
