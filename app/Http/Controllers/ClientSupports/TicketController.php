<?php

namespace App\Http\Controllers\ClientSupports;

use App\Http\Controllers\Controller;
use App\Models\Bills;
use App\Models\IspBillHistorys;
use App\Models\PackageChanges;
use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use App\Models\Tickets;
use App\Models\Clients;

class TicketController extends Controller
{

    protected $departments  = "departments";
    protected $clients      = "clients";
    protected $users        = "users";
    protected $tickets      = "tickets";
    protected $ticket_comments      = "ticket_comments";
    protected $line_shifts      = "line_shifts";
    protected $package_changes      = "package_changes";

    public  function __construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $departments           = DB::table($this->departments)->get();
        $clients              = DB::table($this->clients)->get();

        return view("back.client_support.ticket", compact("clients", "departments"));
    }

    public function save_ticket(Request $request)
    {
        //if($request->action==1){
            $data = array();
            $data["ticket_no"]              = date("d").$request->ref_client_id.date("is");
            $data["ref_client_id"]          = $request->ref_client_id;
            $data["ref_department_id"]      = $request->ref_department_id;
            $data["subject"]                = $request->subject;
            $data["complain"]               = $request->complain;
            $data["ticket_type"]            = 'general';
            $data["opened_by"]              = Auth::user()->id;
            $data["ticket_datetime"]        = date("Y-m-d H:i:s");
            $data["created_at"]             = date("Y-m-d H:i:s");

            $result = DB::table($this->tickets)->insert($data);
       // }

        if($result){
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ticketList(Request $request)
    {

        $columns = array(
            0 => $this->tickets.'.id',
            1 =>'ticket_no',
            2 =>'ref_client_id',
            3 =>'ref_department_id',
            4 =>'ticket_datetime',
            5 =>'ticket_status'
        );

        $totalData = Tickets::company()->count();

        $totalFiltered = $totalData;
        $where = [];
        if($request->ticket_type){
            $where = [
                "ticket_type"   => $request->ticket_type
            ];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Tickets::company()
                //->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
                ->where($where)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   Tickets::company()
//                ->select("*", $this->tickets.".id as ticket_pk_id")
//                ->leftJoin($this->clients,$this->tickets.".ref_client_id","=",$this->clients.".id")
                //->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
                ->where('ticket_no','=',"{$search}")
                ->where($where)
                ->orWhere('client_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  Tickets::company()
                ->where('id','=',"{$search}")
                ->where($where)
                ->orWhere('ticket_no', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->ticket_no;
                $nestedData[] = $post->client? $post->client->client_id."<br>".$post->client->client_name."<br><a href='tel:".$post->client->cell_no."'>".$post->client->cell_no."</a>":"";
                //$nestedData[] = $post->department_name;
                $nestedData[] = $post->subject;
                $nestedData[] = date("h:iA d/m/y", strtotime($post->ticket_datetime));
                $nestedData[] = $post->ticket_status;

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

    public function pending_ticket_list(Request $request)
    {

        $columns = array(
            0 => $this->tickets.'.id',
            1 =>'ticket_no',
            2 =>'ref_client_id',
            3 =>'ref_department_id',
            4 =>'ticket_datetime',
            5 =>'ticket_status'
        );

        $totalData = Tickets::company()->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $where = [
            "ticket_status"=>1
        ];
        if($request->ticket_type){
            $where = [
                "ticket_status" =>1,
                "ticket_type"   => $request->ticket_type
            ];
        }

        if(empty($request->input('search.value')))
        {
            $posts = Tickets::company()
                //->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
                ->where($where)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   Tickets::company()
                //->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
                ->where('ticket_no','=',"{$search}")
                ->where($where)
                ->orWhere('client_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  Tickets::company()
                ->where('id','=',"{$search}")
                ->where($where)
                ->orWhere('ticket_no', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->ticket_pk_id;
                $nestedData[] = $post->ticket_no;
                $nestedData[] = $post->client_id."<br>".$post->client_name."<br><a href='tel:".$post->cell_no."'>".$post->cell_no."</a>";
                //$nestedData[] = $post->department_name;
                $nestedData[] = $post->subject;
                $nestedData[] = date("h:iA d/m/y", strtotime($post->ticket_datetime));
                $nestedData[] = $post->ticket_status;

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

    public function closed_ticket_list(Request $request)
    {

        $columns = array(
            0 => $this->tickets.'.id',
            1 =>'ticket_no',
            2 =>'ref_client_id',
            3 =>'ref_department_id',
            4 =>'ticket_datetime',
            5 =>'ticket_status'
        );

        $totalData = Tickets::company()->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $where = [
            "ticket_status"=>0
        ];
        if($request->ticket_type){
            $where = [
                "ticket_status" =>0,
                "ticket_type"   => $request->ticket_type
            ];
        }
        if(empty($request->input('search.value')))
        {
            $posts = Tickets::company()
                //->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
                ->where($where)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   Tickets::company()
                //->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
                ->where('ticket_no','=',"{$search}")
                ->where($where)
                ->orWhere('client_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  Tickets::company()
                ->where('id','=',"{$search}")
                ->where($where)
                ->orWhere('ticket_no', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->ticket_pk_id;
                $nestedData[] = $post->ticket_no;
                $nestedData[] = $post->client_id."<br>".$post->client_name."<br><a href='tel:".$post->cell_no."'>".$post->cell_no."</a>";
                //$nestedData[] = $post->department_name;
                $nestedData[] = $post->subject;
                $nestedData[] = date("h:iA d/m/y", strtotime($post->ticket_datetime));
                $nestedData[] = $post->ticket_status;

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

    public function ticketView(Request $request)
    {
        $result =  Tickets::find($request->id);
//            ->leftJoin($this->clients,$this->tickets.".ref_client_id","=",$this->clients.".auth_id")
//            ->leftJoin($this->departments,$this->tickets.".ref_department_id","=",$this->departments.".id")
//            ->where($this->tickets.".id",$request->id)
//            ->first();
        if($result){
            return response()->json(array(
                "ticket" =>$result,
                 "client_name" =>$result->client->client_name . " [".$result->client->client_id."]" ,
               "department_name" =>$result->department? $result->department->department_name:"" ,
                "comments" =>json_decode($result->ticket_comment,JSON_UNESCAPED_UNICODE)
            ));
        }else{
            return response()->json([]);
        }
    }

    public function post_ticket_comment(Request $request)
    {
        $comment                    = [];
        $comment["comment_text"]    = $request->comment_text;
        $comment["comment_date"]    = date("Y-m-d H:i:s");
        $comment["ticket_id"]       = $request->ticket_id;
        $comment["user_id"]         = Auth::user()->id;
        $comment["username"]         = Auth::user()->name;

        $tickets = Tickets::where('ticket_no', $request->ticket_id)->firstOrFail();
        $comments=[];
        if($tickets->ticket_comment){
            $comment1 = json_decode($tickets->ticket_comment);
            foreach ($comment1 as $t) {
                $comments[]=$t;
            }
            $comments[]=$comment;
        }else{
            $comments[]=$comment;
        }
        $tickets->ticket_comment=json_encode($comments);
        $tickets->save();

        if($tickets){
            return response()->json($comment);
        } else {
            return response()->json([]);
        }
    }

    public function ticketClose(Request $request)
    {
        $error = 0;
        $ticket = Tickets::company()->whereId($request->id)->first();

        if($ticket->ticket_type=="line_shift"){
            $line_shift_data = DB::table($this->line_shifts)->where("ref_ticket_no",$ticket->ticket_no)->first();
            if($line_shift_data){
                $clients_update = Clients::query()
                    ->where('auth_id',$line_shift_data->ref_client_id)
                    ->update(
                        [
                            "zone_id"   => $line_shift_data->new_zone_id,
                            "node_id"   => $line_shift_data->new_node_id,
                            "box_id"    => $line_shift_data->new_box_id,
                            "address"   => $line_shift_data->new_address,
                            "updated_at"   => date("Y-m-d H:i:s"),
                        ]
                    );

                if($clients_update){
                    $error = 0;
                }else{
                    $error = 1;
                }
            }
        }

        if($ticket->ticket_type=="package_change"){
            $package_change_data = PackageChanges::query()
                ->where("ref_ticket_no",$ticket->ticket_no)->first();
            if($package_change_data){
                $clients_update = Clients::query()
                    ->where('auth_id',$package_change_data->ref_client_id)
                    ->update(
                        [
                            "package_id"            => $package_change_data->new_package_id,
                            "package_change"        => 1,
                            "package_change_date"   => $package_change_data->change_date,
                            "updated_at"            => date("Y-m-d H:i:s"),
                        ]
                    );

                if($clients_update){
                    $dateString = date('Ymd');

                    $bill_type=2;
                    $bill_id=$dateString.$bill_type.$package_change_data->ref_client_id;

                    $today      = date("Y-m-d");
                    $this_month = date("m");
                    $this_year  = date("Y");
                    $new_data=array();
                    $new_data["company_id"]                 = \Settings::company_id();
                    $new_data["particular"]                 = "Package Change Charge";
                    $new_data["client_id"]                  = $package_change_data->ref_client_id;
                    $new_data["client_initial_id"]          = '';
                    $new_data["bill_id"]                    = $bill_id;
                    $new_data["bill_date"]                  = $today;
                    $new_data["bill_month"]                 = $this_month;
                    $new_data["bill_year"]                  = $this_year;
                    $new_data["bill_type"]                  =$bill_type;
                    $new_data["bill_status"]                = 0;
                    $new_data["payable_amount"]             = $package_change_data->package_charge ;
                    $new_data["permanent_discount_amount"]  = 0;
                    $data[]= $new_data;

                    $history=array();
                    $history["particular"]= "Package Change Charge";
                    $history["company_id"]                  = $new_data["company_id"];
                    $history["client_id"]= $new_data["client_id"];
                    $history["bill_id"]= $new_data["bill_id"];
                    $history["bill_year"]= $new_data["bill_year"];
                    $history["bill_month"]= $new_data["bill_month"];
                    $history["bill_amount"]= $new_data["payable_amount"];
                    $history["receive_amount"]= 0;
                    $history["created_at"]= date("Y-m-d H:i");
                    $history["updated_at"]= date("Y-m-d H:i");
                    $historyData[]=$history;
                    if(Bills::query()->insert($data)){
                        IspBillHistorys::query()->insert($historyData);
                    }
                    $error = 0;
                }else{
                    $error = 1;
                }
            }
        }

        if($error==0){
            $result =  Tickets::company()->where("id",$request->id)->update([
                "ticket_status"     =>  0,
                "closed_by"         => Auth::user()->id,
                "close_datetime"    => date("Y-m-d H:i:s")
            ]);
        }else{
            $result =false;
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
