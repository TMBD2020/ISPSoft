<?php

namespace App\Http\Controllers\ClientSupports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;

class UpcomingClientController extends Controller
{
    protected $packages = "packages";
    protected $zones = "zones";
    protected $networks = "microtiks";
    protected $id_types = "id_types";
    protected $id_prefixs = "id_prefixs";
    protected $upcoming_clients = "upcoming_clients";

    public  function __construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $packages           = DB::table($this->packages)->get();
        $zones              = DB::table($this->zones)->get();

        return view("back.client_support.upcoming_client", compact("packages", "zones"));
    }

    public function save_client(Request $request)
    {
        if($request->action==1){
            $data = array();
            $data["ref_zone_id"]        = $request->ref_zone_id;
            $data["client_name"]        = $request->client_name;
            $data["client_address"]     = $request->client_address;
            $data["client_mobile"]      = $request->client_mobile;
            $data["ref_package_id"]     = $request->ref_package_id;
            $data["otc"]                = $request->otc;
            $data["setup_date"]         = date("Y-m-d",strtotime($request->setup_date));
            $data["previous_isp"]       = $request->previous_isp;
            $data["note"]               = $request->note;
            $data["created_at"]         = date("Y-m-d H:i:s");

            $result = DB::table($this->upcoming_clients)->insert($data);
        }else{
            $data = array();
            $data = array();
            $data["ref_zone_id"]        = $request->ref_zone_id;
            $data["client_name"]        = $request->client_name;
            $data["client_address"]     = $request->client_address;
            $data["client_mobile"]      = $request->client_mobile;
            $data["ref_package_id"]     = $request->ref_package_id;
            $data["otc"]                = $request->otc;
            $data["setup_date"]         = date("Y-m-d",strtotime($request->setup_date));
            $data["previous_isp"]       = $request->previous_isp;
            $data["note"]               = $request->note;
            $data["updated_at"]             = date("Y-m-d H:i:s");

            $result = DB::table($this->upcoming_clients)->whereId($request->id)->update($data);

        }

        if($result){
            echo 1;
        } else {
            echo 0;
        }
    }

    public function clientList(Request $request)
    {

        $columns = array(
            0 => $this->upcoming_clients.'.id',
            1 =>'client_name',
            2 =>'ref_zone_id',
            3 =>'ref_package_id',
            4 =>'setup_date'
        );

        $totalData = DB::table($this->upcoming_clients)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->upcoming_clients)
                ->select("*", $this->upcoming_clients.".id as client_pk_id")
                ->leftJoin($this->packages,$this->upcoming_clients.".ref_package_id","=",$this->packages.".id")
                ->leftJoin($this->zones,$this->upcoming_clients.".ref_zone_id","=",$this->zones.".id")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->upcoming_clients)
                ->select("*", $this->upcoming_clients.".id as client_pk_id")
                ->leftJoin($this->packages,$this->upcoming_clients.".ref_package_id","=",$this->packages.".id")
                ->leftJoin($this->zones,$this->upcoming_clients.".ref_zone_id","=",$this->zones.".id")
                ->where('id','=',"{$search}")
                ->orWhere('client_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->upcoming_clients)
                ->where('id','=',"{$search}")
                ->orWhere('client_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->client_pk_id;
                $nestedData[] = $post->client_name."<br>".$post->client_mobile;
                $nestedData[] = $post->zone_name_en."<br>".$post->client_address;
                $nestedData[] = $post->package_name."<br>".$post->package_price;
                $nestedData[] = date("d M,Y", strtotime($post->setup_date));

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

    public function clientUpdate(Request $request)
    {
        $result =  DB::table($this->upcoming_clients)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function clientDelete(Request $request)
    {
        $result = DB::table($this->upcoming_clients)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
