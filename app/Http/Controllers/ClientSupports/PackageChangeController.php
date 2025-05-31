<?php

namespace App\Http\Controllers\ClientSupports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Clients;
use App\Models\Packages;
use App\Models\PackageChanges;

class PackageChangeController extends Controller
{
    protected $clients = "clients";
    protected $packages = "packages";
    protected $package_changes = "package_changes";
    protected $tickets = "tickets";

    public function index(){
        $clients = Clients::where(["company_id"=>\Settings::company_id(),"connection_mode"=>1])->get();
        return view("back.client_support.package_change", compact("clients"));
    }

    public function package_client(Request $request){
        $permanent_discount = 0;
        if($request->param=="old"){
            $client = Clients::where("auth_id",$request->id)->first();
            $packages = Packages::find($client->package_id);
            $permanent_discount = $client->permanent_discount;
        }else{
            $packages = Packages::find($request->id);
        }
        return json_encode([$packages,$permanent_discount]);
    }

    public function all_package(Request $request){
        $packages = Packages::where("id","<>",$request->id)->where(["company_id"=>\Settings::company_id()])->get();
        return json_encode($packages);
    }

    public function migrate_new_package(Request $request){

        $ticket["ticket_no"]    = date("d").$request->ref_client_id.date("is");

        $complain= "New Package : ".$request->new_download ."<br>
        D:".$request->new_download.", U:".$request->new_upload.", Y:".$request->new_youtube.", Q:".$request->new_que_type;

        $complain .= "Current Package : ".$request->current_package ."<br>
        D:".$request->current_download.", U:".$request->current_upload.", Y:".$request->current_youtube.", Q:".$request->current_que_type;

        $ticket["ref_client_id"]          = $request->ref_client_id;
        $ticket["ref_department_id"]      = 0;
        $ticket["subject"]                = "Package Change";
        $ticket["complain"]               = $complain;
        $ticket["opened_by"]              = Auth::user()->id;
        $ticket["company_id"]              = \Settings::company_id();
        $ticket["ticket_type"]            = "package_change";
        $ticket["ticket_datetime"]        = date("Y-m-d H:i:s");
        $ticket["created_at"]             = date("Y-m-d H:i:s");

        $PackageChanges = new PackageChanges();
        $PackageChanges->company_id  = \Settings::company_id();
        $PackageChanges->ref_client_id  = $request->ref_client_id;
        $PackageChanges->new_package_id = $request->new_pack;
        $PackageChanges->old_package_id = $request->old_pack;
       // $PackageChanges->package_price = $request->package_price;
        $PackageChanges->change_date    = date("Y-m-d", strtotime($request->change_date));
        $PackageChanges->package_charge = $request->package_change_charge;
        $PackageChanges->permanent_discount = $request->permanent_discount;
        $PackageChanges->ref_ticket_no  = $ticket["ticket_no"];
        $PackageChanges->is_closed  = $request->change_date==date("Y-m-d")?0:1;
        $PackageChanges->note           = $request->note;
        $PackageChanges->save();

        $package_change_id = $PackageChanges->id;

        if($PackageChanges){
            $ticket_insert = DB::table($this->tickets)->insert($ticket);
            if(!$ticket_insert){
                PackageChanges::find($package_change_id)->delete();
                echo 0;
            }else{
                echo $ticket["ticket_no"];
            }
        }else{
            echo 0;
        }
    }
}
