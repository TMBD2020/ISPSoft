<?php

namespace App\Services;

use App\Models\Bills;
use App\Models\Clients;
use App\Models\IspBillHistorys;
use App\Models\Boxes;
use App\Models\Nodes;
use App\Models\Zones;
use App\Models\Pops;
use App\Models\ClientTypes;
use App\Models\Packages;
use App\Models\IdPrefixs;
use App\Models\SmsTemplates;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Support\Str;
use Hash;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
class ClientService
{
    protected $smsService;
    public function __construct(
        SMSService $smsService
    ) {
        $this->smsService = $smsService;
    }
    public function ispClientImportSave($request, $client_type)
    {
        //return $request->client_thana;
        $boxs = $request->box_id;
        $client_name = $request->client_name;
        $client_id = $request->client_id;
        $mobile_no = $request->mobile_no;
        $alter_mobile_no = $request->alter_mobile_no;
        $email = $request->email;
        $nid = $request->nid;
       
        if (is_array($request->client_address)) {
            $client_address = array_map('addslashes', $request->client_address);
        } else {
            $client_address = addslashes($request->client_address);
        }
        $client_thana = $request->client_thana;
        $dynamic_mac_address = $request->dynamic_mac_address;
        $gpon_mac_address = $request->gpon_mac_address;
        $join_date = $request->join_date;
        $package_id = $request->package_id;
        $previous_bill = $request->previous_bill;
        $permanent_discount = $request->permanent_discount;
        $client_required_cable = $request->client_required_cable;
        $client_status = $request->client_status;
        $payment_dateline = $request->payment_date;
        $billing_date = $request->billing_date;
        $billing_responsible = $request->billing_responsible;
        $isSMS = $request->isSMS;
        if ($client_type == "pppoe") {
            $pppoe_username = $request->pppoe_username;
            $pppoe_password = $request->pppoe_password;
        } else {
            $ip_address = $request->ip_address;
        }
        //DB::beginTransaction();
        try {
            foreach ($boxs as $key => $box) {
                if (trim($box) && trim($client_name[$key])) {
                    // $boxes = Boxes::company()->whereId($box)->first();
                    // if (!$boxes) {
                    //     Log::error('Box not found for ID: ' . $box);
                    //     throw new \Exception('Box not found for ID: ' . $box);
                    // }

                    // $nodes = Nodes::company()->whereId($boxes->ref_node_id)->first();
                    // if (!$nodes) {
                    //     Log::error('Node not found for ID: ' . $boxes->ref_node_id);
                    //     throw new \Exception('Node not found for ID: ' . $boxes->ref_node_id);
                    // }
                    // $zones = Zones::company()->whereId($nodes->ref_zone_id)->first();
                    // if (!$zones) {
                    //     Log::error('Zone not found for ID: ' . $nodes->ref_zone_id);
                    //     throw new \Exception('Zone not found for ID: ' . $nodes->ref_zone_id);
                    // }
                    // $pops = Pops::company()->whereId($zones->pop_id)->first();
                    // if (!$pops) {
                    //     Log::error('POP not found for ID: ' . $zones->pop_id);
                    //     throw new \Exception('POP not found for ID: ' . $zones->pop_id);
                    // }
                    // $clientTypes = ClientTypes::company()->where('default', 1)->first();

                    
                    //$package = Packages::company()->whereId(trim($package_id[$key]))->first();
                    $prefix_id = IdPrefixs::company()->where("ref_id_type_name", 2)->first();

                    if ($prefix_id) {
                        if (trim($client_id[$key]) && isset($client_id[$key])) {
                            $user_id = $prefix_id->id_prefix_name . "-" . trim($client_id[$key]);
                        } else {
                            $totalClient = Clients::query()->where(["company_id" => \Settings::company_id()])->count();
                            $counter = $totalClient + $prefix_id->initial_id_digit;
                            $user_id = $prefix_id->id_prefix_name . "-" . $counter;
                           
                        }
                      
                        $userCount = User::query()->where("user_id", $user_id)->count();
                        Log::error($key);
                        $password = strtolower(string: Str::random(6));
                        if ($userCount == 0 && \Settings::company_id()>0) {
                            $company=\Settings::company_id();
                            $user = User::create([
                                "company_id" => $company,
                                "user_id" => $user_id,
                                "name" => trim(addslashes($client_name[$key])),
                                "email" => $user_id,
                                "user_type"=> 'client',
                                "password" => Hash::make($password)
                            ]);
                            $user=false;
                           // Log::error('user: ' . $user->id);
                            if ($user) {
                                /*
                                if (trim($mobile_no[$key])) {
                                    if (substr(trim($mobile_no[$key]), 0, 1) != 0) {
                                        $mobile_no[$key] = "0" . trim($mobile_no[$key]);
                                    }
                                } else {
                                    $mobile_no[$key] = 0;
                                }
                                if (trim($alter_mobile_no[$key])) {
                                    if (substr(trim($alter_mobile_no[$key]), 0, 1) != 0) {
                                        $alter_mobile_no[$key] = "0" . trim($alter_mobile_no[$key]);
                                    }
                                } else {
                                    $alter_mobile_no[$key] = 0;
                                }

                                $data = [
                                    "company_id" => \Settings::company_id(),
                                    "auth_id" => $user->id,
                                    "client_id" => $user_id,
                                    "client_name" => trim($client_name[$key]),
                                    "box_id" => $boxes? $boxes->id:"",
                                    "node_id" => $nodes ? $nodes->id : '',
                                    "zone_id" => $zones ? $zones->id : '',
                                    "pop_id" => $pops ? $pops->id : '',
                                    "network_id" => $pops ? $pops->ref_network_id : '',
                                    "package_id" =>$package? $package->id:"",
                                    "payment_dateline" => $payment_dateline[$key],
                                    "billing_date" => $billing_date[$key],
                                    "cell_no" => trim($mobile_no[$key]),
                                    "email" => trim($email[$key]),
                                    "nid" => trim($nid[$key]),
                                    "alter_cell_no_1" => trim($alter_mobile_no[$key]),
                                    "permanent_discount" => trim($permanent_discount[$key]) ? trim($permanent_discount[$key]) : 0,
                                    "payment_id" => 1,
                                    "payment_alert_sms" => 0,
                                    "payment_conformation_sms" => 0,
                                    "prefix_id" => 2,
                                    "address" => trim($client_address[$key]) == '' ? $boxes->box_location : trim($client_address[$key]),
                                    "thana" => $zones->zone_thana ? $zones->zone_thana : $client_thana[$key],
                                    "mac_address" => trim($dynamic_mac_address[$key]),
                                    "gpon_mac_address" => trim($gpon_mac_address[$key]),
                                    "join_date" => $join_date[$key],
                                    "connectivity_id" => 1,
                                    "client_type_id" => $clientTypes ? $clientTypes->id : '',
                                    "technician_id" => $billing_responsible[$key] ? $billing_responsible[$key] : $zones->technician_id,
                                    "connection_mode" => $client_status[$key],
                                    "cable_id" => 1,
                                    "required_cable" => trim($client_required_cable[$key])
                                ];
                                if ($client_type == "pppoe") {
                                    $data = array_merge($data, ["pppoe_username" => trim($pppoe_username[$key]), "pppoe_password" => trim($pppoe_password[$key])]);
                                } else {
                                    $data = array_merge($data, ["ip_address" => trim($ip_address[$key])]);
                                }

                                $newClient = Clients::create($data);

                                if ($newClient) {

                                    if (isset($isSMS) && !empty($isSMS)) {
                                        $smsBody = SmsTemplates::company()
                                            ->where(["template_type" => 'welcome', "system" => 1, "temp_status" => 1])
                                            ->first();
                                        $sms_text = \Settings::akbarDyContent(explode(',', $smsBody->keyword), [explode(' ', trim($client_name[$key]))[0], $data["client_id"], $password], $smsBody->template_text);

                                        //   $sms_text = "Dear " . trim($client_name[$key]) . ", thanks to being with us.Your username: " . $user_id . " and Password: " . $password;

                                        $sms_data = [
                                            "to" => $data["cell_no"],
                                            "sms_text" => $sms_text,
                                            "sms_from" => "isp",
                                            "sms_sender" => auth()->user()->name,
                                            "schedule_time" => date("Y-m-d H:i")
                                        ];
                                        $this->smsService->masterSave($sms_data);
                                    }

                                }

                                if ($previous_bill[$key] != "" and $previous_bill[$key] > 0) {
                                    if ($newClient) {
                                        $bill = array();
                                        $bill["receive_date"] = null;
                                        $this_year = date("Y");
                                        $this_month = date("m");
                                        $bill_type = 2;//custom
                                        $bill_count = 1;
                                        $bill_id = $this_year . $this_month . $bill_type . $bill_count . $newClient->id;
                                        $bill["client_id"] = $user->id;
                                        $bill["company_id"] = \Settings::company_id();
                                        $bill["client_initial_id"] = $newClient->client_id;
                                        $bill["bill_id"] = $bill_id;
                                        $bill["particular"] = 'Monthly Bill';
                                        $bill["bill_date"] = date("Y-m-d");
                                        $bill["bill_month"] = $this_month;
                                        $bill["bill_year"] = $this_year;
                                        $bill["bill_type"] = $bill_type;
                                        $bill["bill_status"] = 1;
                                        $bill["bill_approve"] = 1;
                                        $bill["package_id"] = $package->id;
                                        $bill["package_title"] = $package->package_name;
                                        $bill["package_amount"] = $package->package_price;
                                        $bill["previous_amount"] = $previous_bill[$key];
                                        $bill["connection_charge"] = 0;
                                        $bill["permanent_discount_amount"] = trim($permanent_discount[$key]) ? trim($permanent_discount[$key]) : 0;
                                        $bill["payable_amount"] = $previous_bill[$key] ? $previous_bill[$key] : 0;
                                        $bill["discount_amount"] = 0;
                                        $bill["receive_amount"] = 0;
                                        $bill["receive_by"] = 0;

                                        $history = array();
                                        $history["particular"] = "Monthly bill";
                                        $history["company_id"] = \Settings::company_id();
                                        $history["client_id"] = $bill["client_id"];
                                        $history["bill_id"] = $bill["bill_id"];
                                        $history["bill_year"] = $bill["bill_year"];
                                        $history["bill_month"] = $bill["bill_month"];
                                        $history["bill_amount"] = $bill["payable_amount"];
                                        $history["receive_amount"] = 0;
                                        $history["bill_type"] = $bill["bill_type"];
                                        $history["created_at"] = date("Y-m-d H:i");
                                        $history["updated_at"] = date("Y-m-d H:i");


                                        $result = Bills::insert($bill);
                                        if ($result) {
                                            if ($history) {
                                                //  $his = IspBillHistorys::query()->insert($history);
                                            }
                                        }
                                    }
                                }
                                */
                            }
                        }
                    }
                
                }
            }
            //DB::commit();
            return true;
        } catch (\Exception $e) {
            //DB::rollback();
            Log::error('Client insertion error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}