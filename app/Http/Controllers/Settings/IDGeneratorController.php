<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class IDGeneratorController extends Controller
{
    protected  $id_types = "id_types";
    protected  $id_prefixs = "id_prefixs";

    public function idType()
    {
        return view("back.id_generator.id_type");
    }

    public function save_idType(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->id_types)->insert(
                [
                    "company_id"=>auth()->user()->company_id,
                    "id_type_name"  => $request->id_type_name,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "id_type_name"  => $request->id_type_name,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->id_types)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function idTypeList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'id_type_name',
        );

        $totalData = DB::table($this->id_types)
            //->where(["company_id"=>auth()->user()->company_id])
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->id_types)
               // ->where(["company_id"=>auth()->user()->company_id])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->id_types)->where('id','LIKE',"%{$search}%")
                ->orWhere('id_type_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->id_types)
              //  ->where(["company_id"=>auth()->user()->company_id])
                ->where('id','LIKE',"%{$search}%")
                ->orWhere('network_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->id_type_name;
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

    public function idTypeUpdate(Request $request)
    {
        $result =  DB::table($this->id_types)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function idTypeDelete(Request $request)
    {
        $result = DB::table($this->id_types)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
    public function idPrefix()
    {
        $idType = DB::table($this->id_types)
            //->where(["company_id"=>auth()->user()->company_id])
            ->get();
        return view("back.id_generator.id_prefix", compact("idType"));
    }

    public function save_idPrefix(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->id_prefixs)->insert(
                [
                "company_id"=>auth()->user()->company_id,
                    "id_prefix_name"  => $request->id_prefix_name,
                    "initial_id_digit"  => $request->initial_id_digit,
                    "ref_id_type_name"  => $request->ref_id_type_name,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "id_prefix_name"  => $request->id_prefix_name,
                "initial_id_digit"  => $request->initial_id_digit,
                "ref_id_type_name"  => $request->ref_id_type_name,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->id_prefixs)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function idPrefixList(Request $request)
    {

        $columns = array(
            0 =>'prefix_id',
            1 =>'id_prefix_name',
            2 =>'initial_id_digit',
            3 =>'id_type_name',
        );

        $totalData = DB::table($this->id_prefixs)
            ->where(["company_id"=>auth()->user()->company_id])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->id_prefixs)
                ->select("*",$this->id_prefixs.".id as prefix_id")
                ->leftJoin($this->id_types,$this->id_prefixs.".ref_id_type_name","=",$this->id_types.".id")
                ->where(["id_prefixs.company_id"=>auth()->user()->company_id])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->id_prefixs)
                ->where(["company_id"=>auth()->user()->company_id])
                ->where('id','LIKE',"%{$search}%")
                ->orWhere('id_type_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->id_prefixs)
                ->where(["company_id"=>auth()->user()->company_id])
                ->where('id','LIKE',"%{$search}%")
                ->orWhere('network_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->prefix_id;
                $nestedData[] = $post->id_prefix_name;
                $nestedData[] = $post->initial_id_digit;
                $nestedData[] = $post->id_type_name;
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

    public function idPrefixUpdate(Request $request)
    {
        $result =  DB::table($this->id_prefixs)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function idPrefixDelete(Request $request)
    {
        $result = DB::table($this->id_prefixs)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
