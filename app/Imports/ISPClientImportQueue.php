<?php

namespace App\Imports;

use App\Models\Clients;
use App\Models\Bills;
use App\Models\IdPrefixs;
use App\Models\Nodes;
use App\Models\Zones;
use App\Models\Pops;
use App\Models\Boxes;
use App\Models\ClientTypes;
use App\Models\Packages;
use App\Models\IspBillHistorys;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use Illuminate\Support\Str;

class ISPClientImportQueue implements ToCollection,WithHeadingRow
{

  
    public function collection(Collection $rows)
    {
        //dd($rows);
        foreach ($rows as $row) {
            if(trim($row['box_id']) && trim($row['client_name'])){
               $boxes  = Boxes::query()->whereId(trim($row['box_id']))->where(["company_id"=>\Settings::company_id()])->first();
                $nodes  = Nodes::query()->whereId($boxes->ref_node_id)->where(["company_id"=>\Settings::company_id()])->first();
                $zones  = Zones::query()->whereId($nodes->ref_zone_id)->where(["company_id"=>\Settings::company_id()])->first();
                $pops   = Pops::query()->whereId($zones->pop_id)->where(["company_id"=>\Settings::company_id()])->first();
                $clientTypes  = ClientTypes::query()->where('default',1)->where(["company_id"=>\Settings::company_id()])->first();
                if($boxes) {
                    $package = Packages::query()->whereId(trim($row['package_id']))->where(["company_id"=>\Settings::company_id()])->first();
                    $prefix_id = IdPrefixs::query()->where("ref_id_type_name",2)->where(["company_id"=>\Settings::company_id()])->first();
                    //dd($prefix_id);
                    if ($prefix_id) {
                        if (trim($row['client_id'])) {
                            $user_id = $prefix_id->id_prefix_name . "-" . trim($row['client_id']);
                        } else {
                            $totalClient = Clients::query()->count();
                            $counter = $totalClient + $prefix_id->initial_id_digit;
                            $user_id = $prefix_id->id_prefix_name. "-" . $counter;
                        }
                        $userCount = User::query()->where("user_id", $user_id)->count();
                        $password=strtolower(Str::random(6));
                        if ($userCount==0) {
                            $user=new User();
                            $user->company_id=\Settings::company_id();
                            $user->user_id = $user_id;
                            $user->name = trim($row['client_name']);
                            $user->email = $user_id;
                            $user->user_type = 'client';
                            $user->password = Hash::make($password);
                            $user->save();

                            if ($user) {
                                if (trim($row['mobile_no'])) {
                                    if (substr(trim($row['mobile_no']), 0, 1) != 0) {
                                        $row['mobile_no'] = "0" . trim($row['mobile_no']);
                                    }
                                } else {
                                    $row['mobile_no'] = 0;
                                }
                                if (trim($row['alter_mobile_no'])) {
                                    if (substr(trim($row['alter_mobile_no']), 0, 1) != 0) {
                                        $row['alter_mobile_no'] = "0" . trim($row['alter_mobile_no']);
                                    }
                                } else {
                                    $row['alter_mobile_no'] = 0;
                                }
                                if(trim($row['join_date'])){
                                    //$join_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject( trim($row['join_date']))->format('Y-m-d');
                                   // $join_date= date("Y-m-d",strtotime(trim($row['join_date'])));
                                    $join_date= trim($row['join_date']);
                                } else{
                                    $join_date=null;
                                }
                                $data = [
                                    "company_id"=>\Settings::company_id(),
                                    "auth_id" => $user->id,
                                    "client_id" => $user_id,
                                    "client_name" => trim($row['client_name']),
                                    "box_id" => $boxes->id,
                                    "node_id" => $nodes?$nodes->id:'',
                                    "zone_id" => $zones?$zones->id:'',
                                    "pop_id" => $pops?$pops->id:'',
                                    "network_id" => $pops?$pops->ref_network_id:'',
                                    "package_id" => $package->id,
                                    "payment_dateline" => 1,
                                    "billing_date" => 1,
                                    "cell_no" =>  trim($row['mobile_no']),
                                    "email" =>  trim($row['email']),
                                    "nid" =>  trim($row['nid']),
                                    "alter_cell_no_1" =>  trim($row['alter_mobile_no']),
                                    "payment_id" => 1,
                                    "payment_alert_sms" => 0,
                                    "payment_conformation_sms" => 0,
                                    "prefix_id" => 2,
                                    "permanent_discount" => trim($row['permanent_discount'])?trim($row['permanent_discount']):0,
                                    "address" =>  trim($row['client_address'])==''?$boxes->box_location:trim($row['client_address']),
                                    "thana" =>   $zones->zone_thana,
                                    "ip_address" =>  trim($row['ip_address']),
                                    "mac_address" =>  trim($row['dynamic_mac_address']),
                                    "gpon_mac_address" =>  trim($row['gpon_mac_address']),
                                    "join_date" => $join_date,
                                    "client_type_id" =>  $clientTypes?$clientTypes->id:'',
                                    "technician_id" =>  $zones->technician_id,
                                    "connectivity_id" =>  1,
                                    "connection_mode" =>  trim($row['client_status'])=='active'?1:0,
                                    "cable_id" => 1,
                                    "required_cable" =>  trim($row['client_required_cable']),
                                    "note" =>  trim($row['note'])
                                ];

                                $newClient = Clients::create($data);


                                if($newClient){
                                    $sms_text = "Dear ".trim($row['client_name']).", thanks to being with us.
                Your username: ".$user_id." and Password: ".$password;

                                    $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
                                    $sms_data=array(
                                        "to"            =>  $data["cell_no"],
                                        "sms_text"      =>  $sms_text,
                                        "sms_from"      =>  "isp",
                                        "sms_sender"    =>  auth()->user()->name,
                                        "sms_type"      =>  "english",
                                        "sms_api"       =>  null,
                                        "schedule_time" =>  date("Y-m-d H:i")
                                    );
                                    app('App\Http\Controllers\SMS\SMSController')->master_save_sms($sms_data);
                                }

                                if ($row['previous_bill'] != "" and $row['previous_bill'] > 0) {
                                    if ($newClient) {
                                        $bill = array();
                                        $bill["receive_date"] = null;
                                        $this_year = date("Y");
                                        $this_month = date("m");
                                        $bill_type = 2;//custom
                                        $bill_count = 1;
                                        $bill_id = $this_year . $this_month . $bill_type . $bill_count . $newClient->id;
                                        $bill["client_id"] = $user->id;
                                        $bill["client_initial_id"] = $newClient->client_id;
                                        $bill["bill_id"] = $bill_id;
                                        $bill["bill_date"] = date("Y-m-d");
                                        $bill["bill_month"] = $this_month;
                                        $bill["bill_year"] = $this_year;
                                        $bill["bill_type"] = $bill_type;
                                        $bill["package_id"] = $package->id;
                                        $bill["particular"] = 'Monthly Bill';
                                        $bill["package_title"] = $package->package_name;
                                        $bill["package_amount"] = $package->package_price;
                                        $bill["bill_approve"] = 1;
                                        $bill["company_id"] = \Settings::company_id();
                                        $bill["bill_status"] = 1;
                                        $bill["connection_charge"] = 0;
                                        $bill["previous_amount"] = trim($row['previous_bill']);
                                        $bill["permanent_discount_amount"] = trim($row['permanent_discount'])?trim($row['permanent_discount']):0;
                                        $bill["payable_amount"] = trim($row['previous_bill'])?trim($row['previous_bill']):0;
                                        $bill["discount_amount"] = 0;
                                        $bill["receive_amount"] = 0;
                                        $bill["receive_by"] = 0;


                                        $history=array();
                                        $history["particular"]= "Monthly bill";
                                        $history["company_id"] = \Settings::company_id();
                                        $history["client_id"]= $bill["client_id"];
                                        $history["bill_id"]= $bill["bill_id"];
                                        $history["bill_type"]= $bill["bill_type"];
                                        $history["bill_year"]= $bill["bill_year"];
                                        $history["bill_month"]= $bill["bill_month"];
                                        $history["bill_amount"]= $bill["payable_amount"];
                                        $history["receive_amount"]= 0;
                                        $history["created_at"]= date("Y-m-d H:i");
                                        $history["updated_at"]= date("Y-m-d H:i");


                                       $result= Bills::create($bill);
                                        if($result){
                                            if($history){
                                               $his= IspBillHistorys::query()->insert($history);
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
    }
}
