<?php

namespace App\Imports;

use App\Models\User;
use App\Models\SubZones;
use App\Models\CatbClients;
use App\Models\IdPrefixs;
use App\Models\CatvPackages;
use App\Models\Bills;
use App\Models\IspBillHistorys;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class CATVClientImport implements ToCollection,WithHeadingRow
{
    public function collection(Collection $rows)
    {
        //dd($rows);
        foreach ($rows as $row) {
            if(trim($row['sub_zone_id']) && trim($row['client_name'])){
                $subZone = SubZones::find(trim($row['sub_zone_id']));
                if($subZone) {
                    $package = CatvPackages::find(trim($row['package_id']));
                    $prefix_id = IdPrefixs::query()->where("zone_id", $subZone->ref_zone_id)->first();
                    if ($prefix_id) {
                        if (trim($row['client_id'])) {
                            $user_id = $prefix_id->id_prefix_name . "-" . trim($row['client_id']);
                        } else {
                            $totalClient = CatbClients::query()->where("prefix_id", $prefix_id->id)->count();
                            $counter = $totalClient + $prefix_id->initial_id_digit;
                            $user_id = $prefix_id->id_prefix_name. "-" . $counter;
                        }
                        $userCount = User::query()->where("user_id", $user_id)->count();

                        if ($userCount==0) {
                            $user = New User();
                            $user->user_id= $user_id;
                            $user->name = trim($row['client_name']);
                            $user->email = $user_id;
                            $user->company_id = \Settings::company_id();
                            $user->is_admin = 0;
                            $user->user_type = "catb_client";
                            $user->password = Hash::make(123456);
                            $user->created_at=date("Y-m-d H:i:s");
                            $user->updated_at=date("Y-m-d H:i:s");
                            $user->save();

                            if ($user) {
                                if (trim($row['mobile_no'])) {
                                    if (substr(trim($row['mobile_no']), 0, 1) != 0) {
                                        $row['mobile_no'] = "0" . trim($row['mobile_no']);
                                    }
                                } else {
                                    $row['mobile_no'] = 0;
                                }

                                if(trim($row['join_date'])){
                                    //$join_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject( trim($row['join_date']))->format('Y-m-d');
                                    $join_date=str_replace("/","-",trim($row['join_date']));
                                    $join_date= date("Y-m-d",strtotime($join_date));
                                } else{
                                    $join_date=null;
                                }
                                $data = [
                                    "company_id"=>\Settings::company_id(),
                                    "auth_id" => $user->id,
                                    "client_id" => $user_id,
                                    "client_name" => trim($row['client_name']),
                                    "home_card_no" =>  trim($row['card_no']),
                                    "zone_id" => $subZone->ref_zone_id,
                                    "sub_zone_id" => $subZone->id,
                                    "prefix_id" => $prefix_id->id,
                                    "payment_dateline" => 1,
                                    "billing_date" => 1,
                                    "cell_no" =>  trim($row['mobile_no']),
                                    "package_id" => $package->id,
                                    "payment_id" => 1,
                                    "otc" => 0,
                                    "mrp" => $package->price,
                                    "payment_alert_sms" => 0,
                                    "payment_conformation_sms" => 0,
                                    "address" =>  trim($row['client_address']),
                                    "thana" => $subZone->thana,
                                    "join_date" =>$join_date,
                                    "connection_mode" =>  trim($row['client_status']),
                                    "cable_id" => 1,
                                    "required_cable" =>  trim($row['client_required_cable']),
                                    "note" =>  trim($row['note'])
                                ];

                                $newClient = CatbClients::create($data);

                                if ($row['previous_bill'] != "" and $row['previous_bill'] > 0) {
                                    if ($newClient) {
                                        $bill = array();
                                        $bill["receive_date"] = null;
                                        $this_year = date("Y");
                                        $this_month = date("m");
                                        $bill_type = 1;
                                        $bill_count = 1;
                                        $bill_id = $this_year . $this_month . $bill_type . $bill_count . $newClient->id;
                                        $bill["company_id"] = \Settings::company_id();
                                        $bill["client_id"] = $newClient->id;
                                        $bill["client_initial_id"] = $newClient->client_id;
                                        $bill["bill_id"] = $bill_id;
                                        $bill["bill_date"] = date("Y-m-d");
                                        $bill["bill_month"] = $this_month;
                                        $bill["bill_year"] = $this_year;
                                        $bill["bill_type"] = $bill_type;
                                        $bill["package_id"] = $package->id;
                                        $bill["client_type"] = 2;
                                        $bill["bill_status"] = 1;
                                        $bill["previous_amount"] = $row['previous_bill'];
                                        $bill["payable_amount"] = $row['previous_bill'];
                                        $bill["discount_amount"] = 0;
                                        $bill["receive_amount"] = 0;
                                        $bill["receive_by"] = 0;



                                        if(Bills::create($bill)){
                                            $history=array();
                                            $history["company_id"]=\Settings::company_id();
                                            $history["particular"]= "Monthly bill";
                                            $history["client_id"]= $bill["client_id"];
                                            $history["bill_id"]= $bill["bill_id"];
                                            $history["bill_year"]= $bill["bill_year"];
                                            $history["bill_month"]= $bill["bill_month"];
                                            $history["bill_amount"]= $bill["payable_amount"];
                                            $history["receive_amount"]= 0;
                                            $history["client_type"]= 2;
                                            $history["created_at"]= date("Y-m-d H:i");
                                            $history["updated_at"]= date("Y-m-d H:i");

                                            IspBillHistorys::create($history);
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
