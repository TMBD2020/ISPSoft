<?php

namespace App\Http\Controllers\ClientSupports;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Employees;
use Illuminate\Http\Request;
use App\Models\Packages;
use App\Models\Zones;
use App\Models\CatbClients;

class ClientCustomUpdate extends Controller
{
    public function index(){
        $zones              = Zones::query()->where('company_id',\Settings::company_id())->get();
        $clients=$zone_id=$employees=$client_id=$client_type ="";
        return view('back.client_support.custom_edit',compact('zones','clients','zone_id','employees','client_id','client_type'));
    }
    public function search(Request $request){
        $zones = Zones::query()->where('company_id',\Settings::company_id())->get();
        $employees = Employees::query()->where('company_id',\Settings::company_id())->get();
        $client_type=$request->client_type;
        $zone_id=$request->zone_id;
        $client_id=$request->client_id;
        if($client_type==1){
            $clients = Clients::query()
                ->where('company_id',\Settings::company_id());

            if($zone_id){
                $clients = $clients->where('zone_id',$zone_id);
            }
            if($client_id){
                $clientid=explode(",",$client_id);
                $newCid=[];
                foreach ($clientid as $c) {
                    $newCid[]=trim(strtoupper($c));
                }

                $clients = $clients->whereIn('client_id',$newCid);
            }
               $clients =   $clients->get();
        }elseif($client_type==2){
            // $clients = CatbClients::query()
            //     ->where('company_id',\Settings::company_id());
            //   if($zone_id){
            //       $clients = $clients->where('zone_id',$zone_id);
            //   }
            // if($client_id){
            //     $clientid=explode(",",$client_id);
            //     $newCid=[];
            //     foreach ($clientid as $c) {
            //         $newCid[]=trim(strtoupper($c));
            //     }

            //     $clients = $clients->whereIn('client_id',$newCid);
            // }
            //     $clients = $clients->get();
             $clients = [];
        }


        return view('back.client_support.custom_edit',compact('zones','clients','zone_id','employees','client_id','client_type'));
    }

    public function save(Request $request){
         if($client_type==1){
            $client_type=$request->client_type;
            $zone_id=$request->zone_id;
            $client_id=$request->client_id;//user_id
            $id=$request->id;//primary key
    
            foreach ($id as $key=>$client) {
                $clients = Clients::find($client);
                $clients->cell_no=$request->cell_no[$key];
                $clients->billing_responsible=$request->billing_responsible[$key];
                $clients->technician_id=$request->technician_id[$key];
                $clients->lat_long=$request->lat_long[$key];
                $clients->olt_interface=$request->olt_interface[$key];
                $clients->mac_address=$request->mac_address[$key];
                $clients->required_cable=$request->required_cable[$key];
                $clients->receive_power=$request->receive_power[$key];
                $clients->save();
            }
    
            return redirect()->back()->with("msg","Successfully saved");

        }
            return redirect()->back()->with("msg","Saved failed. Try again leter!");
    }


}
