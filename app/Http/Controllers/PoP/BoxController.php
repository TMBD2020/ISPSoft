<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class BoxController extends Controller
{
    protected $zones = "zones";
    protected $nodes = "nodes";
    protected $boxes = "boxes";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        $nodes = DB::table($this->nodes)->where("company_id",\Settings::company_id())->get();
        return view("back.pop.box", compact("nodes"));
    }

    public function save_box(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->boxes)->insert(
                [
                    "box_name"     => $request->box_name,
                    "company_id"    =>  \Settings::company_id(),
                    "box_location"     => $request->box_location,
                    "total_port"     => $request->total_port,
                    "ref_node_id"            => $request->ref_node_id,
                    "onu_details"     => $request->onu_details,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "box_name"     => $request->box_name,
                "box_location"     => $request->box_location,
                "total_port"     => $request->total_port,
                "ref_node_id"            => $request->ref_node_id,
                "onu_details"     => $request->onu_details,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->boxes)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function boxList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'box_name',
            2 =>'box_location',
            3 =>'total_port',
        );

        $totalData = DB::table($this->boxes)->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->boxes)->where(["company_id"=>\Settings::company_id()])->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->boxes)
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('box_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->boxes)
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('box_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->box_name;
                $nestedData[] = $post->box_location;
                $nestedData[] = $post->total_port;
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

    public function boxUpdate(Request $request)
    {
        $result =  DB::table($this->boxes)
            ->where("id",$request->id)
            ->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function boxByNode(Request $request)
    {
        $result =  DB::table($this->boxes)->where("ref_node_id",$request->id)->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function boxDelete(Request $request)
    {
        $result = DB::table($this->boxes)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
