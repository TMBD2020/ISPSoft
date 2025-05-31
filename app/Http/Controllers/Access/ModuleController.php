<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

use App\Models\Modules;
use App\Models\SubModules;
use App\Models\AccessPermissions;
use Illuminate\Http\Request;
use Auth;
use DB;

class ModuleController extends Controller
{
    public function index(){
        $modules=Modules::all();
        return view("back.access.module",compact('modules'));
    }

    public function save_module(Request $request)
    {
        if($request->action==1){
           $result= Permission::create(
                [
                    'head' => $request->head,
                    'name' => $request->permission_name
                ]
            );

        }else{
            $permission = Permission::find($request->id);
            $permission->head=$request->head;
            $permission->name=$request->permission_name;
            $result=$permission->save();
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function moduleList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'name',
        );

        $totalData = Permission::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Permission::query()
//                ->where([
//                    "company_id"     => auth()->user()->company_id])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   Permission::query()
//                ->where([
//                    "company_id"     => auth()->user()->company_id])
                //->where('id','LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  Permission::query()
//                ->where([
//                    "company_id"     => auth()->user()->company_id])
//                ->where('id','LIKE',"%{$search}%")
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
                $nestedData[] = $post->module? $post->module->module_name:'';
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

    public function updateModule(Request $request)
    {
        $result =  Permission::find($request->id);
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }


    public function deleteModule(Request $request){
        $module=Modules::find($request->id);
        if($module->delete()){
            AccessPermissions::query()->where("module_id",$request->id)->delete();
            SubModules::query()->where("module_id",$request->id)->delete();
        }
    }

    public static function m(){
        return Modules::query()
            ->select(DB::raw("DISTINCT modules.id as moduleId, modules.module_name, modules.module_link, modules.module_icon"))
            ->leftJoin("access_permissions","modules.id","access_permissions.module_id")
            ->where(["role_id"=>Auth::user()->ref_role_id,"read_access"=>1])
            ->orderBy("module_order","asc")
            ->get();
    }
    public static function s($moduleId){
        return SubModules::query()
            ->select(DB::raw("sub_modules.id as subModuleId,sub_module_name, sub_module_link"))
            ->leftJoin("access_permissions","sub_modules.id","access_permissions.sub_module_id")
            ->where(["role_id"=>Auth::user()->ref_role_id,"read_access"=>1,"ref_module_id"=>$moduleId])
            ->get();
    }
    public static function ms(){
        return Modules::query()
            ->select(
                [
                    DB::raw("DISTINCT modules.id as moduleId"),
                    "module_name",
                    "module_link",
                    "module_icon",
                    "read_access",
                    "write_access",
                    "update_access",
                    "delete_access",
                    "approve_access"
                ]
            )
            ->leftJoin("access_permissions","modules.id","access_permissions.module_id")
            ->where(["role_id"=>Auth::user()->ref_role_id,"read_access"=>1])
            ->orderBy("module_order","asc")
            ->get();
    }
    public static function ss(){
        return SubModules::query()
            ->select(
                [
                    "sub_modules.id as subModuleId",
                    "sub_modules.ref_module_id as moduleId",
                    "sub_module_name",
                    "sub_module_link",
                    "read_access",
                    "write_access",
                    "update_access",
                    "delete_access",
                    "approve_access"
                ]
            )
            ->leftJoin("access_permissions","sub_modules.id","access_permissions.sub_module_id")
            ->where(["role_id"=>Auth::user()->ref_role_id,"read_access"=>1])
            ->get();
    }

}
