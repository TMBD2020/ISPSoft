<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Boxes;
use App\Models\Pops;
use App\Models\Zones;
use App\Models\SubZones;
use App\Models\Bills;
use App\Models\SmsTemplates;
use App\Models\Nodes;
use App\Models\Packages;
use App\Models\SmsHistory;
use App\Models\TmbdUsers;
use Instasent\SMSCounter\SMSCounter;
use App\Services\SMSService;
class SMSController extends Controller
{

    protected $smsCounter;
    protected $smsService;


    public function __construct(
        SMSCounter $smsCounter,
        SMSService $smsService
    ) {
        $this->smsCounter = $smsCounter;
        $this->smsService = $smsService;
    }
    public function index($type = '')
    {
        $sms_templates = SmsTemplates::company()->get();
        $packages = Packages::company()->get();
        $pops = Pops::company()->get();
        $isp_zones = Zones::company()->where(["zone_type" => 1])->get();
        $catv_zones = Zones::company()->where(["zone_type" => 2])->get();
        $nodes = Nodes::company()->get();
        $boxes = Boxes::company()->get();
        $subzones = SubZones::company()->get();
        return view("back.sms.index", compact("sms_templates", 'type', "packages", "pops", "subzones", "isp_zones", "catv_zones", "nodes", "boxes"));
    }


    public function sms_client_list(Request $request)
    {

        $panel = $request->panel;
        $pop = $request->pop_id;
        $zone = $request->zone_id;
        $subzone_id = $request->subzone_id;
        $node = $request->node_id;
        $box = $request->box_id;
        $packages = $request->package_id;
        $status = $request->client_status;

        $wherePop = $whereZone = $whereSubZone = $whereNode = $whereBox = $wherePackage = "";

        if (is_array($pop)) {
            if (!in_array(0, $pop)) {
                $wherePop = " pop_id in (" . implode(',', $pop) . ") and ";
            }
        }
        if (is_array($zone)) {
            if (!in_array(0, $zone)) {
                $whereZone = " zone_id in (" . implode(',', $zone) . ") and ";
            }
        }
        if (is_array($subzone_id)) {
            if (!in_array('all', $subzone_id)) {
                $whereSubZone = " sub_zone_id =" . $subzone_id . " and ";
            }
        }
        if (is_array($node)) {
            if (!in_array(0, $node)) {
                $whereNode = " node_id in (" . implode(',', $node) . ") and ";
            }
        }
        if (is_array($box)) {
            if (!in_array(0, $box)) {
                $whereBox = " box_id in (" . implode(',', $box) . ") and ";
            }
        }
        if (is_array($packages)) {
            if (!in_array(0, $packages)) {
                $wherePackage = " package_id in (" . implode(',', $packages) . ") and";
            }
        }

        if ($panel == 'isp') {
            $clients = DB::select(DB::raw("SELECT * FROM employees WHERE " .
                $wherePop . $whereZone . $whereNode . $whereBox . $wherePackage . " connection_mode in ($status)"));
        } else {
            $clients = DB::select(DB::raw("SELECT * FROM catb_clients WHERE " .
                $whereZone . $whereSubZone . " connection_mode in ($status)"));
        }

        if (count($clients) > 0) {
            echo json_encode($clients);
        } else {
            echo 0;
        }


    }

    public function sms_preview(Request $request)
    {
        $company_id=\Settings::company_id();
        $panel = $request->sms_panel;
        $pop = $request->pop_id;
        $zone = $request->zone_id;
        $subzone_id = $request->subzone_id;
        $node = $request->node_id;
        $box = $request->box_id;
        $packages = $request->package_id;
        $status = $request->client_status;
        $sms_text = $request->sms_text;
        $selected_sms = $request->selected_sms;
        $client_id = $request->clients;
        $schedule_time = $request->schedule_time;
        $filter_by = $request->filter_by;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $request->session()->put('pop_id', $pop);
        $request->session()->put('zone_id', $zone);
        $request->session()->put('node_id', $node);
        $request->session()->put('box_id', $box);
        $request->session()->put('package_id', $packages);
        $request->session()->put('client_status', $status);
        $request->session()->put('sms_text', $sms_text);
        $request->session()->put('clients', $client_id);
        $request->session()->put('schedule_time', $schedule_time);
        $request->session()->put('filter_by', $filter_by);
        $request->session()->put('date_from', $date_from);
        $request->session()->put('$date_to', $date_to);

        $whereClause = "1";

        if (is_array($pop)) {
            if (!in_array(0, $pop)) {
                $whereClause .= " and pop_id in (" . implode(',', $pop) . ")";
            }
        }
        if (is_array($zone)) {
            if (!in_array(0, $zone)) {
                $whereClause .= " and zone_id in (" . implode(',', $zone) . ")";
            }
        }
        if ($subzone_id) {
            if ('all' != $subzone_id) {
                $whereClause .= " and sub_zone_id =" . $subzone_id . "";
            }
        }
        if (is_array($node)) {
            if (!in_array(0, $node)) {
                $whereClause .= " and node_id in (" . implode(',', $node) . ")";
            }
        }
        if (is_array($box)) {
            if (!in_array(0, $box)) {
                $whereClause .= " and box_id in (" . implode(',', $box) . ")";
            }
        }
        if (is_array($packages)) {
            if (!in_array(0, $packages)) {
                $whereClause .= " and package_id in (" . implode(',', $packages) . ")";
            }
        }
        if ($status) {
            $whereClause .= " and connection_mode = $status";            
        }
        if ($filter_by) {
            if($filter_by==1){
                $whereClause .= " and c.termination_date between '$date_from' and '$date_to'"; 
            }
            if($filter_by==2){
                $date_from=date("d", strtotime($request->date_from));
                $date_to=date("d", timestamp: strtotime($request->date_to));
                $whereClause .= " and c.payment_dateline between '$date_from' and '$date_to'"; 
            }
            if($filter_by==3){
                $date_from=date("d", strtotime($request->date_from));
                $date_to=date("d", strtotime($request->date_to));
                $whereClause .= " and c.billing_date between '$date_from' and '$date_to'"; 
            }        
        }

        if ($client_id == 0) {
            // if ($panel == "isp") {
            //     $billTitle = "internet";
            //     $billTable = 'bills';
            //     $clients = DB::select(DB::raw("SELECT * FROM clients WHERE " .
            //         $wherePop . $whereZone . $whereNode . $whereBox . $wherePackage . " connection_mode in ($status)"));
            // } else {
            //     $billTitle = "dish";
            //     $billTable = 'catb_bills';
            //     $status = $request->catv_client_status;
            //     $clients = DB::select(DB::raw("SELECT * FROM catb_clients WHERE " .
            //         $whereZone . $whereSubZone . " connection_mode in ($status)"));
            // }

            $sql="
        
                SELECT sum(debit) - sum(credit) as due_amount, q.client_id, c.client_id username,c.id, c.client_name,c.cell_no, c.connection_mode, IFNULL(c.permanent_discount,0) permanent_discount,c.payment_dateline,c.billing_date,c.termination_date FROM (

                SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE company_id =$company_id GROUP BY client_id
                
                UNION ALL
                
                SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit,client_id FROM bill_receives WHERE company_id =$company_id GROUP BY client_id

                ) AS q 
                INNER JOIN clients c ON c.auth_id=q.client_id AND c.company_id=$company_id
                where $whereClause group by q.client_id having due_amount>0

            ";

            $sms_data = array();
            $sms_data1 = array();
            $dueBills=  DB::select( $sql);
                foreach ($dueBills as $dueBill) {
                    //$content = str_replace(['{{client_name}}', '{{client_id}}', '{{due_amount}}'], [explode(" ",$row[1])[0], $row[2], $row[0]], $sms_text);
                    $sms_body_text = \Settings::akbarDyContent(
                        ['{{client_name}}', '{{client_id}}', '{{due_amount}}'],
                        [explode(" ", $dueBill->client_name)[0], $dueBill->username, $dueBill->due_amount],
                        $sms_text
                    );

                    //$sms_body_text=config('constants.developer.name');

                    $new_array = array();
                    $new_array["sms_text"] = $sms_body_text;
                    $new_array["filter_by"] = $filter_by;
                    $new_array["payment_dateline"] = $dueBill->payment_dateline;
                    $new_array["termination_date"] = $dueBill->termination_date;
                    $new_array["billing_date"] = $dueBill->billing_date;
                    $new_array["client_name"] = $dueBill->username ."<br>". $dueBill->client_name;
                    $new_array["cell_no"] = $dueBill->cell_no;
                    $new_array["id"] = $dueBill->id;
                    $new_array["schedule_time"] = $schedule_time;
                    $new_array["sms_count"] = $this->smsCounter->count($sms_body_text)->messages;
                    $sms_data[] = $new_array;
                }

            

        } else {
            if ($panel == "isp") {
                $billTitle = "internet";
                $clients = DB::select(DB::raw("SELECT * FROM " . $this->clients . " WHERE  id = $client_id and  connection_mode in ($status)"));
            } else {
                $billTitle = "dish";
                $status = $request->catv_client_status;
                $clients = DB::select(DB::raw("SELECT * FROM catb_clients WHERE connection_mode in ($status)"));
            }
            $custom_msg = $sms_text;
            $custom_msgs = [];
            $has_placeholder = true;
            while ($has_placeholder) {
                $first_position = strpos($custom_msg, "{{");
                if ($first_position || $first_position === 0) {
                    $custom_msgs[] = substr($custom_msg, 0, $first_position);
                    $last_position = strpos($custom_msg, "}}");
                    $custom_msg = substr($custom_msg, $last_position + 2);
                } else {
                    $custom_msgs['last'] = $custom_msg;
                    $has_placeholder = false;
                }
            }
            $sms_data = array();
            $sms_data1 = array();
            foreach ($clients as $client) {
                $dueBills = Bills::select(
                    DB::raw('(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as due_amount, count(*) as total')
                )->where(
                        [
                            "client_id" => $client->auth_id,
                            //     "bill_status"   =>  0,
                        ]
                    )->groupBy("client_id")
                    ->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)))"), ">", 0)
                    ->get();

                foreach ($dueBills as $dueBill) {
                    $new_array = array();
                    $new_array[] = $dueBill->due_amount;
                    $new_array[] = explode(" ", $client->client_name)[0];
                    $new_array[] = $client->client_id;
                    $new_array[] = $client->cell_no;
                    $new_array[] = $client->id;
                    $new_array[] = $schedule_time;
                    $sms_data1[] = $new_array;
                }
            }
            $sms_body_text = '';
            $placeholder_position_in_col = explode(",", $request->col_index);
            foreach ($sms_data1 as $row) {
                if (count($custom_msgs) > 1) {
                    foreach ($placeholder_position_in_col as $key => $value) {
                        if (!$custom_msgs[$key]) {
                            $sms_body_text .= $row[$value];
                        } else {
                            $sms_body_text .= $custom_msgs[$key] . $row[$value];

                        }
                    }
                }

                $sms_body_text .= $custom_msgs['last'];
                $new_array = array();
                $new_array["sms_text"] = $sms_body_text;
                $new_array["client_name"] = $row[1];
                $new_array["cell_no"] = $row[2];
                $new_array["id"] = $row[4];
                $new_array["schedule_time"] = $row[5];
                $new_array["sms_count"] = $this->smsCounter->count($sms_body_text)->messages;
                $sms_data[] = $new_array;
                $sms_body_text = '';
            }
        }

        return view("back.sms.sms_preview", compact("sms_data", "sms_text", "panel"));
    }

    public function save_sms(Request $request)
    {

        $client_type = $request->client_type;
        $sms_value = $request->sms_value;
        $sms_sender = $request->sms_sender;
        //$sms_type = "english";
        $sms_status = "Pending";
        $sms_api = "null";
        $created_at = date("Y-m-d H:i");

        $data = array();

        for ($i = 0; $i < count($sms_value); $i++) {
            $value = explode("^", $sms_value[$i]);
            $cell_no = $value[0];
            $sms_text = $value[1];

            $sms_count = $this->smsCounter->count($sms_text)->messages;
            $sms_type = $this->smsCounter->count($sms_text)->encoding;
            if ($value[2]) {
                $sms_schedule_time = date("Y-m-d H:i", strtotime(str_replace("/", "-", $value[2])));
                if (strtotime($sms_schedule_time) > strtotime($created_at)) {
                    $sent_time = "";
                } else {
                    $sent_time = $sms_schedule_time;
                }
            } else {
                $sms_schedule_time = $created_at;
                $sent_time = $sms_schedule_time;
            }

            $data[] = "(			
                '" . \Settings::company_id() . "',
                '" . $cell_no . "',
                '" . $sms_sender[0] . "',
                '" . $sms_count . "',
                '" . $sms_text . "',
                '" . $client_type . "',
                '" . $sms_type . "',
                '" . $sms_status . "',
                '" . $this->smsService->getAPIid() . "',
                '" . $sms_schedule_time . "',
                '" . $sent_time . "',
                '" . $created_at . "'
            )";
        }

        
        $result = DB::insert("insert into sms_history
         (company_id,sms_receiver,sms_sender,sms_count,sms_text,client_type,sms_type,sms_status,sms_api,sms_schedule_time,sent_time,created_at)
         values " . implode(",", $data));

        if ($result) {
            $request->session()->put('pop_id', "");
            $request->session()->put('zone_id', "");
            $request->session()->put('node_id', "");
            $request->session()->put('box_id', "");
            $request->session()->put('package_id', "");
            $request->session()->put('client_status', "");
            $request->session()->put('sms_text', "");
            $request->session()->put('clients', "");
            $request->session()->put('schedule_time', "");
            return redirect()->route("send-sms",[$client_type])->with("message", "SMS successfully sent.");
        } else {
            $request->session()->put('pop_id', "");
            $request->session()->put('zone_id', "");
            $request->session()->put('node_id', "");
            $request->session()->put('box_id', "");
            $request->session()->put('package_id', "");
            $request->session()->put('client_status', "");
            $request->session()->put('sms_text', "");
            $request->session()->put('clients', "");
            $request->session()->put('schedule_time', "");
        }
        return redirect()->route("send-sms",[$client_type])->with("error", "Failed!.");
    }

    public function send_sms_from_client(Request $request)
    {

        $client_type = $request->client_type;
        if ($client_type == "catv") {
            $table = "catv_clients";
        } else {
            $table = "clients";
        }

        $clients = DB::table($table)->whereId($request->sms_receiver_id)->first();

        if ($clients) {
            $cell_no = $clients->cell_no;
            $sms_text = trim($request->sms_text);
            $sms_count = $this->smsCounter->count($sms_text)->messages;
            $sms_type = $this->smsCounter->count($sms_text)->encoding;
            $sms_from = $client_type;
            $sms_sender = Auth::user()->name;
            $sms_status = "Pending";
            $sms_api = "null";
            $created_at = date("Y-m-d H:i");

            if ($request->schedule_time) {
                $sms_schedule_time = str_replace("/", "-", $request->schedule_time);
                $sms_schedule_time = date("Y-m-d H:i", strtotime($sms_schedule_time));
                if (strtotime($sms_schedule_time) > strtotime($created_at)) {
                    $sent_time = "";
                } else {
                    $sent_time = $sms_schedule_time;
                }
            } else {
                $sms_schedule_time = $created_at;
                $sent_time = $sms_schedule_time;
            }
            $data = [
                "company_id" => \Settings::company_id(),
                "client_type" => $client_type,
                "sms_receiver" => $cell_no,
                "sms_sender" => $sms_sender,
                "sms_count" => $sms_count,
                "sms_type" => $sms_type,
                "sms_text" => $sms_text,
                "sms_status" => $sms_status,
                "sms_schedule_time" => $sms_schedule_time,
                "sms_api" => $this->smsService->getAPIid(),
                "sent_time" => $sent_time,    
                "created_at" => $created_at
            ];
            $result = SmsHistory::query()->insert($data);
            if ($result) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function master_save_sms($sms_data)
    {
        $company_id = \Settings::company_id();
        $cell_no = $sms_data["to"];
        $sms_text = $sms_data["sms_text"];
        $sms_count = round(strlen($sms_text) / 160) == 0 ? 1 : round(strlen($sms_text) / 160);
        $sms_from = $sms_data["sms_from"];
        $sms_sender = $sms_data["sms_sender"];
        $sms_type = $sms_data["sms_type"];
        $sms_status = "Pending";
        $sms_api = $sms_data["sms_api"];
        $created_at = date("Y-m-d H:i");
        $schedule_time = $sms_data["schedule_time"];

        $sms_schedule_time = date("Y-m-d H:i", strtotime($schedule_time));
        if (strtotime($schedule_time) > strtotime($created_at)) {
            $sent_time = $sms_schedule_time;
        } else {
            $sent_time = $sms_schedule_time;
        }

        $data = [
            "company_id" => $company_id,
            "sms_receiver" => $cell_no,
            "sms_sender" => $sms_sender,
            "sms_count" => $sms_count,
            "sms_type" => $sms_type,
            "client_type" => $sms_from,
            "sms_text" => $sms_text,
            "sms_api" => $sms_api,
            "sms_status" => $sms_status,
            "sms_schedule_time" => $sms_schedule_time,
            "sent_time" => $sent_time,
            "created_at" => $created_at
        ];

        $result = DB::table($this->sms_history)->insert($data);

        if (!$result) {
            echo "sms send error";
        }
    }

    public function send_general_sms(Request $request)
    {
        $company_id = \Settings::company_id();
        $cell_no = explode(',', trim($request->sms_receiver));
        $sms_text = $request->sms_text;
        $sms_count = $this->smsCounter->count($sms_text)->messages;
        $sms_type = $this->smsCounter->count($sms_text)->encoding;
        $sms_sender = auth()->user()->name;
        $sms_status = "Pending";
        $created_at = date("Y-m-d H:i");

        $sms_schedule_time = date("Y-m-d H:i");
        $data = [];
        foreach ($cell_no as $mobile) {
            $data[] = [
                "company_id" => $company_id,
                "sms_receiver" => $mobile,
                "sms_sender" => $sms_sender,
                "sms_count" => $sms_count,
                "sms_type" => $sms_type,
                "client_type" => 'other',
                "sms_text" => $sms_text,
                "sms_status" => $sms_status,
                "sms_schedule_time" => $sms_schedule_time,
                "sms_api" => $this->smsService->getAPIid(),
                "created_at" => $created_at
            ];
        }
        if ($data) {
            $result = SmsHistory::query()->insert($data);
            if (!$result) {
                echo json_encode(["status" => false]);
            } else {
                echo json_encode(["status" => true]);
            }
        } else {
            echo json_encode(["status" => false]);
        }
    }

    public function test_page()
    {
        return '<form action="' . (route('sms_test')) . '" method="post"><input type="hidden" name="_token" value="' . csrf_token() . '"><input type="text" name="to"><input type="text" name="msg"><input type="submit" name="submit" value="Send"></form>';

    }
    public function test(Request $request)
    {
        $to = trim($request->to);
        $message = trim($request->msg);
        if ($to && $message) {
            $user = TmbdUsers::find(\Settings::company_id());
            $apiData = DB::table("sms_api")->find($user->sms_api_id);
            if ($apiData) {
                $api_url = $apiData->api_url;
                if ($user) {
                    if (strlen($to) == 11) {
                        $to = "88" . $to;
                        if (strlen($to) == 13) {
                            $sms_receiver = $to;
                            $msg = $message;
                            $api_url = \Settings::akbarDyContent(["{{to}}","{{msg}}"], [$to, $message],$api_url);
                            $request = curl_init($api_url);
                            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
                            // curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
                            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
                            $post_response = curl_exec($request);
                            curl_close($request);
                            echo $post_response;
                        }
                    }
                }
            }
        }
    }
}
