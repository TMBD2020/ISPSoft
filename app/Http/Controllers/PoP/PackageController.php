<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\CatvPackages;
use App\Models\Packages;

class PackageController extends Controller
{

    protected $clients = "clients";
    protected $packages = "packages";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        return view("back.pop.package");
    }

    public function save_package(Request $request)
    {
        if($request->action==1){
            $result = Packages::query()->insert(
                [
                    "profile_name"  => $request->profile_name,
                    "package_name"  => $request->package_name,
                    "package_price" => $request->package_price,
                    "download"      => $request->download,
                    "upload"        => $request->upload,
                    "youtube"       => $request->youtube,
                    "que_type"       => $request->que_type,
                    "package_type"       => $request->package_type,
                    "is_active"     => 1,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s"),
                    "company_id"=>\Settings::company_id()
                ]
            );
        }else{
            $data = [
                "profile_name"  => $request->profile_name,
                "package_name"  => $request->package_name,
                "package_price" => $request->package_price,
                "download"      => $request->download,
                "upload"        => $request->upload,
                "youtube"       => $request->youtube,
                "que_type"       => $request->que_type,
                "package_type"       => $request->package_type,
                "is_active"     => 1,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            ];
            $result = Packages::query()->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        } else {
            echo 0;
        }
    }

    public function packageList(Request $request)
    {
        $columns = array(
            0 =>'id',
            3 =>'package_price',
            5 =>'package_type',
        );

        $totalData = Packages::query()->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Packages::query()
                ->where(["company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   Packages::query()
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('package_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  Packages::query()
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('package_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->profile_name;
                $nestedData[] = $post->package_name;
                $nestedData[] = $post->package_price;
                $nestedData[] = "D:".$post->download." | U:". $post->upload." | Y:". $post->youtube;
                $nestedData[] = ucfirst($post->package_type);
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

    public function packageUpdate(Request $request)
    {
        $result =  Packages::query()->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function packageDelete(Request $request)
    {
        $result = Packages::query()->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
    public function catv_pack_index()
    {
        return view("back.pop.catv_package");
    }

    public function catv_pack_save_package(Request $request)
    {
        if($request->action==1){
            $result = CatvPackages::query()->insert(
                [
                    "company_id"=>\Settings::company_id(),
                    "name"  => $request->package_name,
                    "price" => $request->package_price,
                    "status"     => 1,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data = [
                "name"  => $request->package_name,
                "price" => $request->package_price,
                "status"     => 1,
                "updated_at"    => date("Y-m-d H:i:s")
            ];
            $result = CatvPackages::query()->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        } else {
            echo 0;
        }
    }

    public function catv_pack_list(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'name',
            3 =>'price',
        );

        $totalData = CatvPackages::query()->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = CatvPackages::query()
                ->where(["company_id"=>\Settings::company_id()])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   CatvPackages::query()
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  CatvPackages::query()
                ->where('id','LIKE',"%{$search}%")
                ->where(["company_id"=>\Settings::company_id()])
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->name;
                $nestedData[] = $post->price;
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

    public function catv_pack_update(Request $request)
    {
        $result =  CatvPackages::query()->whereId($request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function catv_pack_delete(Request $request)
    {
        $result = CatvPackages::query()->whereId($request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
