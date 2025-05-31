<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubZones;
use Auth;
use DB;

class ZoneController extends Controller
{
    protected $zones = "zones";
    protected $employees = "employees";
    protected $networks = "microtiks";
    protected $pops = "pops";

    public  function __construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $employees = DB::table($this->employees)->where(["company_id"=>auth()->user()->company_id])->where("is_resign","0")->get();
        $networks = DB::table($this->networks)->where(["company_id"=>auth()->user()->company_id])->where("is_active","1")->get();
        $pops = DB::table($this->pops)->where(["company_id"=>auth()->user()->company_id])->where("is_active","1")->get();
        return view("back.pop.zone", compact("employees","networks","pops"));
    }

    public function save_zone(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->zones)->insert(
                [
                    "company_id"=>auth()->user()->company_id,
                    "zone_name_en"     => $request->zone_name_en,
                    "zone_name_bn"     => "",
                    "area_incharge"     => $request->area_incharge,
                    "technician_id"            => $request->technician_id,
                    "zone_thana"            => $request->zone_thana,
                    "pop_id"            => $request->pop_id,
                    "ref_network_id"            => $request->ref_network_id,
                    "zone_type"     => $request->zone_type,
                    "is_active"     => $request->is_active,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "zone_name_en"     => $request->zone_name_en,
                "zone_name_bn"     => "",
                "area_incharge"     => $request->area_incharge,
                "zone_thana"     => $request->zone_thana,
                "pop_id"            => $request->pop_id,
                "technician_id"            => $request->technician_id,
                "ref_network_id"            => $request->ref_network_id,
                "zone_type"     => $request->zone_type,
                "is_active"     => $request->is_active,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->zones)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function IspZoneList(Request $request)
    {

        $columns = array(
            0 =>$this->zones.'.id',
            1 =>'zone_name_en',
            2 =>$this->zones.'.ref_network_id',
            3 =>'pop_id',
        );

        $totalData = DB::table($this->zones)->where(["company_id"=>auth()->user()->company_id])->where("zone_type",1)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->zones)
                ->select($this->zones.".id as zone_id",$this->zones.".*",$this->pops.".*",$this->networks.".*")
                ->leftJoin($this->pops,$this->pops.".id","=",$this->zones.".pop_id")
                ->leftJoin($this->networks,$this->zones.".ref_network_id","=",$this->networks.".id")
                ->where("zone_type",1)
                ->where(["zones.company_id"=>auth()->user()->company_id])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->zones)
                ->select($this->zones.".id as zone_id",$this->zones.".*",$this->pops.".*",$this->networks.".*")
                ->leftJoin($this->pops,$this->pops.".id","=",$this->zones.".pop_id")
                ->leftJoin($this->networks,$this->zones.".ref_network_id","=",$this->networks.".id")
                ->where("zone_type",1)
                ->where(["zones.company_id"=>auth()->user()->company_id])
                ->where($this->zones.'.id','LIKE',"%{$search}%")
                ->orWhere('zone_name_en', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->zones)
                ->select($this->zones.".id as zone_id","*")
                ->leftJoin($this->pops,$this->pops.".id","=",$this->zones.".pop_id")
                ->leftJoin($this->networks,$this->zones.".ref_network_id","=",$this->networks.".id")
                ->where("zone_type",1)
                ->where(["zones.company_id"=>auth()->user()->company_id])
                ->where($this->zones.'.id','LIKE',"%{$search}%")
                ->orWhere('zone_name_en', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->zone_id;
                $nestedData[] = $post->zone_name_en;
                $nestedData[] = $post->network_name;
                $nestedData[] = $post->pop_name;
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

    public function catv_index()
    {
        $employees = DB::table($this->employees)
            ->where(["company_id"=>auth()->user()->company_id])->where("is_resign","0")->get();
        return view("back.pop.catv_zone", compact("employees"));
    }

    public function CatbZoneList(Request $request)
    {

        $columns = array(
            0 =>$this->zones.'.id',
            1 =>'zone_name_en',
            2 =>$this->zones.'.ref_network_id',
            3 =>'pop_id',
        );

        $totalData = DB::table($this->zones)
            ->where(["zones.company_id"=>auth()->user()->company_id])->where("zone_type",2)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->zones)
                ->select($this->zones.".id as zone_id",$this->zones.".*",$this->pops.".*",$this->networks.".*")
                ->leftJoin($this->pops,$this->pops.".id","=",$this->zones.".pop_id")
                ->leftJoin($this->networks,$this->zones.".ref_network_id","=",$this->networks.".id")
                ->where("zone_type",2)
                ->where(["zones.company_id"=>auth()->user()->company_id])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->zones)
                ->select($this->zones.".id as zone_id",$this->zones.".*",$this->pops.".*",$this->networks.".*")
                ->leftJoin($this->pops,$this->pops.".id","=",$this->zones.".pop_id")
                ->leftJoin($this->networks,$this->zones.".ref_network_id","=",$this->networks.".id")
                ->where($this->zones.'.id','LIKE',"%{$search}%")
                ->where(["zones.company_id"=>auth()->user()->company_id])
                ->where("zone_type",2)
                ->orWhere('zone_name_en', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->zones)
                ->select($this->zones.".id as zone_id","*")
                ->leftJoin($this->pops,$this->pops.".id","=",$this->zones.".pop_id")
                ->leftJoin($this->networks,$this->zones.".ref_network_id","=",$this->networks.".id")
                ->where("zone_type",2)
                ->where(["zones.company_id"=>auth()->user()->company_id])
                ->where($this->zones.'.id','LIKE',"%{$search}%")
                ->orWhere('zone_name_en', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->zone_id;
                $nestedData[] = $post->zone_name_en;
                $nestedData[] = $post->network_name;
                $nestedData[] = $post->pop_name;
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

    public function zoneUpdate(Request $request)
    {
        $result =  DB::table($this->zones)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function zoneByNetwork(Request $request)
    {
        $result =  DB::table($this->zones)
            ->where(["zones.company_id"=>auth()->user()->company_id])
            ->where("ref_network_id",$request->id)->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function zoneByPOP(Request $request)
    {
        $result =  DB::table($this->zones)
            ->where(["zones.company_id"=>auth()->user()->company_id])->where("pop_id",$request->id)->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function CatvZoneList(Request $request)
    {
        $result =  DB::table($this->zones)->where(["zones.company_id"=>auth()->user()->company_id])->where("zone_type",2)->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function zoneDelete(Request $request)
    {
        $result = DB::table($this->zones)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function subZone(Request $request)
    {
        $subzones    =   SubZones::query()
            ->where(["company_id"=>\Settings::company_id()])->where("ref_zone_id",$request->id)->get();
        if(count($subzones)>0){

            echo json_encode($subzones);
        }else{
            echo 0;
        }
    }
}
