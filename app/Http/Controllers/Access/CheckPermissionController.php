<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AccessPermissions;
use DB;
use Auth;

class CheckPermissionController extends Controller
{
    public static function module($module_id,$access){


        if(auth()->user()->user_type=="admin"){
            return true;
        }

        $permissions = \Session::get('permissions');
        if(!$permissions){
            return false;
        }


        foreach ($permissions as $val) {
            if($val->module_id==$module_id){
                return $val->{$access};
            }

        }

    }
    public static function sub_module($sub_module_id,$access){


        if(auth()->user()->user_type=="admin"){
            return true;
        }
        $permissions = \Session::get('permissions');
        if(!$permissions){
            return false;
        }

        foreach ($permissions as $val) {
            if($val->sub_module_id==$sub_module_id) {
                return $val->{$access};
            }
        }
    }

    public static function permission_session(){
        $permissions = AccessPermissions::query()
            ->where(["company_id"=>auth()->user()->company_id])
            ->where("role_id",Auth::user()->ref_role_id)
            ->get();
        if(!$permissions){
            return false;
        }
        else{
            \Session::put('permissions',$permissions);
            return true;
        }
    }
}
