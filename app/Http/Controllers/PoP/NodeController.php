<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;


class NodeController extends Controller
{

    protected $node_id = "node_id";
    protected $zones = "zones";
    protected $nodes = "nodes";
    protected $boxes = "boxes";

    public  function __construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $zones = DB::table($this->zones)
            ->where("zone_type",1)
            ->where(["company_id"=>\Settings::company_id()])
            ->get();
        return view("back.pop.node", compact("zones"));
    }

    public function save_node(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->nodes)->insert(
                [
                    "company_id"=>\Settings::company_id(),
                    "node_id"       => $request->node_id,
                    "node_name"     => $request->node_name,
                    "node_location" => $request->node_location,
                    "node_splitter" => $request->node_splitter,
                    "ref_zone_id"   => $request->ref_zone_id,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
            if($result){
                DB::table($this->node_id)->where("node_id",$request->node_id)->update(["node_id_status"=>1]);
            }
        }else{
            $result = DB::table($this->nodes)->whereId($request->id)->update(
                [
                   // "node_id"       => $request->node_id,
                    "node_name"     => $request->node_name,
                    "node_location" => $request->node_location,
                    "node_splitter" => $request->node_splitter,
                    "ref_zone_id"   => $request->ref_zone_id,
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function saveNodeId(Request $request)
    {
        $err=0;
        for($i=0;$i<$request->id_limit;$i++){
            $id = $request->new_node_id+$i;
            $result = DB::table($this->node_id)->insert(
                [
                    "company_id"=>\Settings::company_id(),
                    "node_id"       => $id,
                    "node_id_status"=> 0,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
            if(!$result){
                $err=1;
            }else{
                $err=0;
            }
        }
        if($err==0){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function lastNodeId()
    {
        $result = DB::table($this->node_id)->where(["company_id"=>\Settings::company_id()])->orderBy("id","desc")->first();
        if(!$result){
            echo 0;
        }else{
            echo $result->node_id+1;
        }
    }

    public function nodeList(Request $request)
    {

        $columns = array(
            0 =>$this->nodes.'.id',
            1 =>'node_name',
            2 =>'node_location',
            3 =>'ref_zone_id',
            4 =>'node_splitter',
        );

        $totalData = DB::table($this->nodes)->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->nodes)
                ->select($this->nodes.".id as nodes_id", $this->zones.".*", $this->nodes.".*")
                ->leftJoin($this->zones,$this->zones.".id","=",$this->nodes.".ref_zone_id")
                ->where(["nodes.company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->nodes)
                ->select($this->nodes.".id as nodes_id","*")
                ->leftJoin($this->zones,$this->zones.".id","=",$this->nodes.".ref_zone_id")
                ->where($this->nodes.'.id','LIKE',"%{$search}%")
                ->where(["nodes.company_id"=>\Settings::company_id()])
                ->orWhere('node_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->nodes)
                ->select($this->nodes.".id as nodes_id","*")
                ->leftJoin($this->zones,$this->zones.".id","=",$this->nodes.".ref_zone_id")
                ->where($this->nodes.'.id','LIKE',"%{$search}%")
                ->where(["nodes.company_id"=>\Settings::company_id()])
                ->orWhere('node_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->nodes_id;
                $nestedData[] = $post->node_id;
                $nestedData[] = $post->node_name;
                $nestedData[] = $post->node_location;
                $nestedData[] = $post->zone_name_en;
                $nestedData[] = $post->node_splitter;
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

    public function nodeIdDataList(Request $request)
    {
        $columns = array(
            0 =>'node_id',
            1 =>'node_id_status',
            2 =>'ref_voucher_id'
        );

        $totalData = DB::table($this->node_id)->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->node_id)
->where(["company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->node_id)
                ->Where(["company_id"=>\Settings::company_id()])
                ->Where('node_id','LIKE',"%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->node_id)
                ->Where('node_id', 'LIKE',"%$search%")
                ->Where(["company_id"=>\Settings::company_id()])
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->node_id;
                $nestedData[] = $post->node_id_status==1?"<b class='text-danger'>Used</b>":"<b class='text-success'>Available</b>";
                $nestedData[] = $post->ref_voucher_id;
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

    public function nodeIdList(Request $request)
    {
        $result =  DB::table($this->node_id)->where("node_id_status",0)->where(["company_id"=>\Settings::company_id()])->orderBy("node_id","desc")->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function nodeUpdate(Request $request)
    {
        $result =  DB::table($this->nodes)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function nodeByZone(Request $request)
    {
        if($request->id){
            $result =  DB::table($this->nodes)->where("ref_zone_id",$request->id)->where(["company_id"=>\Settings::company_id()])->get();
            if($result){
                echo json_encode($result);
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }

    }

    public function nodeDelete(Request $request)
    {
        $result = DB::table($this->nodes)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
