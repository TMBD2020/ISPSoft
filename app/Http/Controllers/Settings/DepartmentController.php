<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class DepartmentController extends Controller
{
    protected $departments = "departments";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        return view("back.settings.department");
    }

    public function save_department(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->departments)->insert(
                [
                "company_id"=>auth()->user()->company_id,
                    "department_name"     => $request->department_name,
                    "is_active"     => 1,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "department_name"     => $request->department_name,
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->departments)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function departmentList(Request $request)
    {

        $columns = array(
            1 =>'department_name',
        );

        $totalData = DB::table($this->departments)->where(["company_id"=>auth()->user()->company_id])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->departments)
                ->where(["company_id"=>auth()->user()->company_id])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }  else {
            $search = trim($request->input('search.value'));

            $posts =   DB::table($this->departments)
                ->where(["company_id"=>auth()->user()->company_id])
                ->Where('department_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->departments)
                ->where(["company_id"=>auth()->user()->company_id])
                ->Where('department_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $key=> $post)
            {
                $nestedData = array();

                $nestedData[] = ($key+1);
                $nestedData[] = $post->department_name;
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                    <button id="' . $post->id . '" class="update btn btn-primary btn-sm badge"> <span class="ft-edit"></span></button> 
                                </div>';
                                //<button  id=' . $post->id . ' class="deleteData btn btn-danger btn-sm badge"> <span class="ft-delete"></span></button>
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

    public function departmentUpdate(Request $request)
    {
        $result =  DB::table($this->departments)->whereId($request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function departmentDelete(Request $request)
    {
        $result = DB::table($this->departments)->whereId($request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
