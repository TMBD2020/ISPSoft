<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class AccessController extends Controller
{
    protected $roles = "roles";
    protected $modules ="modules";
    protected $sub_modules ="sub_modules";
    protected $access_permissions ="access_permissions";

    public function index(){
        $roles= DB::table($this->roles)
            ->where(["company_id"=>\Settings::company_id()])->get();
        return view("back.access.permission",compact("roles"));
    }

    public function modulePermission(Request $request){
        $role=$request->role_id;
        $main_module = DB::table($this->modules)
            ->where(["company_id"=>auth()->user()->company_id])
            ->orderBy("id","asc")
            ->get();
        $sub_module = DB::table($this->sub_modules)
            ->where(["company_id"=>auth()->user()->company_id])
            ->orderBy("id","asc")
            ->get();
        $permissions = DB::table($this->access_permissions)
            ->where(["company_id"=>auth()->user()->company_id])
            ->where("role_id",$role)
            ->get();

        if($main_module or $sub_module){
            if($main_module){
                $module = $main_module;
            }else{
                $module = 0;
            }
            if($sub_module){
                $subModule = $sub_module;
            }else{
                $subModule = 0;
            }
            $permission = [
                "module"=>$module,
                "subModule"=>$subModule,
                "permissions"=>$permissions,
            ];
        }else{
            $permission = [
                "module"=>0,
                "subModule"=>0,
                "permissions"=>0,
            ];
        }
        echo json_encode($permission);
    }
    public function savePermission(Request $request){
        $permission_id = $request->permission;
        $read_access = $request->read_access;
        $write_access = $request->write_access;
        $update_access = $request->update_access;
        $delete_access = $request->delete_access;
        $approve_access = $request->approve_access;


        for($i=0;$i<count($permission_id);$i++){
            $data = [
                "read_access"=>$read_access[$i],
                "write_access"=>$write_access[$i],
                "update_access"=>$update_access[$i],
                "delete_access"=>$delete_access[$i],
                "approve_access"=>$approve_access[$i]
            ];
            DB::table($this->access_permissions)
                ->whereId($permission_id[$i])
                ->update($data);
        }


    }
}
