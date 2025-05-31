<?php

namespace App\Http\Controllers\PoP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
class PopCategoryController extends Controller
{

    public function index()
    {
        return view("back.pop.pop_category");
    }

    public function save_pop_category(Request $request)
    {
        if($request->action==1){
            $result = DB::table("pop_categories")->insert(
                [
                    "pop_category_name"     => $request->pop_category_name,
                    "created_by"    => Auth::user()->id,
                    "is_active"     => $request->is_active,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "pop_category_name"     => $request->pop_category_name,
                "updated_by"    => Auth::user()->id,
                "is_active"     => $request->is_active,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table("pop_categories")->where('id', $request->id)->update($data);
        }
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function pop_categoryList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'pop_category_name',
            2=> 'is_active'
        );

        $totalData = DB::table("pop_categories")->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table("pop_categories")->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table("pop_categories")->where('id','LIKE',"%{$search}%")
                ->orWhere('pop_category_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table("pop_categories")->where('id','LIKE',"%{$search}%")
                ->orWhere('pop_category_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->pop_category_name;
                $nestedData[] = $post->is_active==1 ? "Yes" : "No";
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

    public function pop_categoryUpdate(Request $request)
    {
        $result =  DB::table("pop_categories")->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function pop_categoryDelete(Request $request)
    {
        $result = DB::table("pop_categories")->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
