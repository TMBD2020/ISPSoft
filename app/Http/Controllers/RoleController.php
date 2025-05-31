<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;
use App\Models\Modules;

class RoleController extends Controller
{
    function __construct()
    {
//        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:role-create', ['only' => ['create','store']]);
//        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $modules = Modules::get();
        $permission = Permission::get();
       
        return view("back.admin.role",compact('permission','modules'));
    }

    public function save_role(Request $request)
    {
        if($request->action==1){
            $role = Role::create(["name"=>$request->role_name,"company_id"=>\Settings::company_id()]);
            $role->syncPermissions($request->permission);

        }else{
            $role =Role::find($request->role_id);
            $role->name=$request->role_name;
            $role->save();
            $role->syncPermissions($request->permission);
        }
        if($role){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function roleList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'name',
        );

        $totalData = Role::query()->where(["company_id"=>\Settings::company_id()])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Role::query()
                ->where(["company_id"=>\Settings::company_id()])
                //->where("id","<>","1")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   Role::query()
                ->where(["company_id"=>\Settings::company_id()])
                ->Where('name', 'LIKE',"%{$search}%")
                //->where("id","<>","1")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  Role::query()->where('name','LIKE',"%{$search}%")->count();
        }

        $data = array();
        if(!empty($posts))
        {
            $i=count($posts)+1;
            foreach ($posts as $key=>$post)
            {

                if($dir=='desc'){
                    $i--;
                }else{
                    $i=($key+1);
                }
                $nestedData = array();

                $nestedData[] = $i;
                $nestedData[] = $post->name;
                $nestedData[] = '<div class="btn-group align-top" role="group">
                    <button id="' .$post->id. '" class="update btn btn-primary btn-sm badge">
                    <span class="ft-edit"></span> Edit</button>
                </div>';
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

    public function roleUpdate(Request $request)
    {
        $result =  Role::find($request->id);
        $rolePermissions = DB::table("role_has_permissions")
        ->where("role_has_permissions.role_id",$request->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
            return response()->json([$result,$rolePermissions]);

    }

    public function roleDelete(Request $request)
    {
        $result = Role::find($request->id);
        $result=$result->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
