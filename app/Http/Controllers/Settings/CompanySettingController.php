<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Image;
use Auth;
use App\Models\TmbdUsers;
use App\Models\Modules;
use App\Models\SmsBalances;

class CompanySettingController extends Controller
{
    protected  $companies = "tmbd_users";
    protected  $themecolors = "themecolors";

    public function index(){
        $setting = TmbdUsers::find(auth()->user()->company_id);
        return view("back.settings.company_setting", compact("setting"));
    }
    public function order_module(){
        $modules = Modules::query()->where("company_id",auth()->user()->company_id)->orderBy("module_order","asc")->get();

        return view("back.settings.sidebar_order", compact("modules"));
    }
    public function order_module_save(Request $request){
        $page_id = $request->page_id_array;

        for($i=0; $i<count($page_id); $i++)
        {
            Modules::query()->where(["id"=>$page_id[$i]])->update(["module_order"=>$i]);
        }
    }

    public function save(Request $request){
        $logo="";
        if ($request->hasFile("company_logo")) {
            $file = $request->file("company_logo");
            $logo = 'app-assets/images/company/company_logo.' . $file->guessClientExtension();
            $request->company_logo->move(public_path('app-assets/images/company/'), $logo );
        }

        $user = TmbdUsers::find(auth()->user()->company_id);
        $user->name =   $request->company_name;
        $user->mobile =   $request->contact_number;
        $user->email_id =   $request->company_email;
        $user->address =   $request->contact_address;
        if($logo){
            $user->logo =   $logo;
        }
        $user->save();

        return back()->with('success','Company Setting Successfully Saved.');
    }

    public static function settings(){
        if(auth()->user()->user_type=="super" || !auth()->user()){
            $setting=array();
            $setting["company_name"] = "Tech Makers BD";
            $setting["company_logo"] = 'app-assets/images/company/default_company_logo.png';
            $setting["contact_number"] = '01837023812';
            $setting["company_email"] = 'info@techmakesbd.com';
            $setting["contact_address"] = 'Agrabad, Chattogram';
            $setting["sms_balance"] =  0 ;
            return $setting;
        }

        $settings = TmbdUsers::find(auth()->user()->company_id);
        $setting=array();
        $setting["company_name"] = $settings->name;
        $setting["company_logo"] = $settings->logo;
        $setting["contact_number"] = $settings->mobile;
        $setting["company_email"] = $settings->email_id;
        $setting["contact_address"] = $settings->address;
        $setting["sms_balance"] =  $settings->sms_balance ;


        return $setting;
    }

    public static function theme(){
        $color = array();
        $colors = DB::table("themecolors")->where("user_id",Auth::user()->id);
        if($colors->count()>0){
            $colors = $colors->first();
            $color["header_bg_color_1"] = $colors->header_bg_color_1;
            $color["header_bg_color_2"] = $colors->header_bg_color_2;
        }else{
            $color["header_bg_color_1"] = "9f78ff";
            $color["header_bg_color_2"] = "32cafe";
        }

        return $color;
    }

    public static function company_id(){
        $company_id=null;
        if(auth()->user()->user_type=="reseller") {
            $company_id = auth()->user()->id;
        }
        else {
            $company_id=auth()->user()->company_id;
        }
        return $company_id;
    }
    public static function akbarDyContent($searchArr,$replaceArr,$content){

        //$content = str_replace(['{{client_name}}', '{{client_id}}', '{{due_amount}}'], [explode(" ",$row[1])[0], $row[2], $row[0]], $sms_text);
        $akbarDyContent = str_replace($searchArr, $replaceArr, $content);

        return $akbarDyContent;
    }
}
