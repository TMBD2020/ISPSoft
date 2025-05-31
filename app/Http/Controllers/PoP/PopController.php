<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class PopController extends Controller
{

    protected  $pops = "pops";
    protected  $pop_categories = "pop_categories";
    protected  $station = "microtiks";
    protected  $employees = "employees";

    public function index()
    {
        $pop_categories = DB::table($this->pop_categories)->where(["company_id"=>\Settings::company_id()])->where('is_active',1)->get();
        $stations = DB::table($this->station)->where(["company_id"=>\Settings::company_id()])->where('is_active',1)->get();
        $employees = DB::table($this->employees)->where(["company_id"=>\Settings::company_id()])->where('is_active',1)->get();
        return view("back.pop.pop", compact("pop_categories","stations","employees"));
    }

    public function save_pop(Request $request)
    {
        if($request->action==1){
            $result = DB::table("pops")->insert(
                [
                    "company_id"=>\Settings::company_id(),
                    "pop_name"          => $request->pop_name,
                    "pop_address"       => $request->pop_address,
                    "ref_cat_id"        => $request->ref_cat_id,
                    "ref_network_id"        => $request->ref_network_id,
                    "power_token"       => $request->power_token,
                    "pop_device_details"=> $request->pop_device_details,
                    "ref_emp_id"       => $request->ref_emp_id,
                    "client_pop"        => $request->client_pop,
                    "created_by"        => Auth::user()->id,
                    "is_active"         => 1,
                    "created_at"        => date("Y-m-d H:i:s"),
                    "updated_at"        => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "pop_name"          => $request->pop_name,
                "pop_address"       => $request->pop_address,
                "ref_cat_id"        => $request->ref_cat_id,
                "ref_network_id"        => $request->ref_network_id,
                "power_token"       => $request->power_token,
                "pop_device_details"=> $request->pop_device_details,
                "ref_emp_id"       => $request->ref_emp_id,
                "client_pop"        => $request->client_pop,
                "updated_by"    => Auth::user()->id,
                "is_active"     => 1,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table("pops")->where('id', $request->id)->update($data);
        }
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function popList(Request $request)
    {

        $columns = array(
            0 => $this->pops.'.id',
            1 =>'pop_name',
            2=> 'pop_address',
            3=> 'ref_cat_id',
            4=> 'ref_network_id',
            5=> 'power_token'
        );

        $totalData = DB::table($this->pops)->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->pops)
                ->select("*", $this->pops.".id as pop_id")
                ->leftJoin($this->pop_categories,$this->pops.".ref_cat_id","=",$this->pop_categories.".id")
                ->leftJoin($this->station,$this->pops.".ref_network_id","=",$this->station.".id")
                ->where(["pops.company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->pops)
                ->select("*", $this->pops.".id as pop_id")
                ->leftJoin($this->pop_categories,$this->pops.".ref_cat_id","=",$this->pop_categories.".id")
                ->leftJoin($this->station,$this->pops.".ref_network_id","=",$this->station.".id")
                ->where($this->pops.'.id','LIKE',"%{$search}%")
                ->where(["pops.company_id"=>\Settings::company_id()])
                ->orWhere('pop_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->pops)
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('pop_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->pop_id;
                $nestedData[] = $post->pop_name;
                $nestedData[] = $post->pop_address;
                $nestedData[] = $post->pop_category_name;
                $nestedData[] = $post->network_name;
                $nestedData[] = $post->power_token;
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

    public function popUpdate(Request $request)
    {
        $result =  DB::table("pops")->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function popByNetwork(Request $request)
    {
        $result =  DB::table("pops")->where("ref_network_id",$request->id)->where(["company_id"=>\Settings::company_id()])->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function popDelete(Request $request)
    {
        $result = DB::table("pops")->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
