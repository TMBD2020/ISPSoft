<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\CatvPackages;


class CatvPackageController extends Controller
{
    public function catv_index()
    {
        return view("back.pop.sub_zone");
    }

    public function catv_sub_zone(Request $request)
    {
        $columns = array(
            0 =>'sub_zones.id',
            1 =>'sub_zone_name',
            2 =>'ref_zone_id',
            3 =>'thana'
        );

        $totalData = SubZones::query()->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = SubZones::query()
                ->select("sub_zones.id as sub_zone_id", "zones.*", "sub_zones.*")
                ->leftJoin("zones","zones.id","=","sub_zones.ref_zone_id")
                ->where('zone_type',2)
                ->where(["subz_zones.company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   SubZones::query()
                ->select("sub_zones.id as sub_zone_id", "zones.*", "sub_zones.*")
                ->leftJoin("zones","zones.id","=","sub_zones.ref_zone_id")
                ->where('zone_type',2)
                ->where(["sub_zones.company_id"=>\Settings::company_id()])
                ->where('sub_zones.id','LIKE',"%{$search}%")
                ->orWhere('sub_zone_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  count($posts);
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->sub_zone_id;
                $nestedData[] = $post->sub_zone_name;
                $nestedData[] = $post->zone_name_en;
                $nestedData[] = $post->thana;
                $nestedData[] = $post->sub_zone_location;
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

    public function update_sub_zone(Request $request)
    {
        $result =   SubZones::query()->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }


    public function delete_sub_zone(Request $request)
    {
        $result = SubZones::query()->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function save_sub_zone(Request $request)
    {
        if($request->action==1){
            $result = SubZones::query()->insert(
                [
                    "sub_zone_name"     => $request->sub_zone_name,
                    "company_id"=>\Settings::company_id(),
                    "ref_zone_id"     => $request->ref_zone_id,
                    "sub_zone_location"            => $request->sub_zone_location,
                    "thana"            => $request->thana,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "sub_zone_name"     => $request->sub_zone_name,
                "ref_zone_id"     => $request->ref_zone_id,
                "sub_zone_location"            => $request->sub_zone_location,
                "thana"            => $request->thana,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = SubZones::query()->where(["id"=>$request->id])->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
