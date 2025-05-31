<?php

namespace App\Http\Controllers;

use App\Models\Boxes;
use App\Models\CableTypes;
use App\Models\Employees;
use App\Models\IdPrefixs;
use App\Models\IdTypes;
use App\Models\Microtiks;
use App\Models\Packages;
use App\Models\PaymentMethods;
use App\Models\Zones;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use App\Models\Bills;
use App\Models\BillReceives;
use App\Models\Clients;
use App\Models\IspResellers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ISPClientImportQueue;
use App\Models\IspBillHistorys;
use App\Models\SmsTemplates;
use Illuminate\Support\Str;

use App\Services\ClientService;
use App\Services\DefaultService;

use Auth;
use DB;
use Hash;
use PDF;
class ClientController extends Controller
{
    protected $companies = "companies";
    protected $pops = "pops";
    protected $packages = "packages";
    protected $zones = "zones";
    protected $networks = "microtiks";
    protected $payment_method = "payment_methods";
    protected $connectivity_types = "connectivity_types";
    protected $cable_types = "cable_types";
    protected $boxes = "boxes";
    protected $client_types = "client_types";
    protected $technicians = "employees";
    protected $clients = "clients";
    protected $id_types = "id_types";
    protected $id_prefixs = "id_prefixs";
    protected $bills = "bills";
    protected $sms_template = "sms_templates";
    protected $clientService;
    protected $DefalutDataSave;

    public function __construct(
        ClientService $clientService,
        DefaultService $DefaultService,
    ) {
        $this->clientService = $clientService;
        $this->DefaultService = $DefaultService;
    }



    public function printpdf()
    {
        $data = Clients::query()->where('company_id', \Settings::company_id())->orderBy('id', "asc")->get();
        $pdf = PDF::loadView('back.clients.isp.print', compact('data'), [], [
            'mode' => 'utf-8',
            'orientation' => 'L',
            'default_font_size' => '9',
            'format' => 'A4',
            'title' => 'ISP Clients',
            //'margin_top' => 0
        ]);
        return $pdf->stream('clients.pdf');
    }

    public function isp_client_import(Request $request)
    {
        $client_type=$request->client_type;
        $boxes = Boxes::company()->get();
        $packages = Packages::company()->get();  
        $technicians = Employees::company()->get();
        // Excel::import(new ISPClientImportPPPoE, $request->file('file'));

        $excel = Excel::toArray([], $request->file('file'))[0];
        $headers = $excel[0];
        $data = [];

        foreach ($excel as $key => $datum) {
            if ($key > 0) {
                $data[] = array_combine($headers, $datum);
            }
        }
        if($client_type=="pppoe"){
            return view("back.imports.pppoe", compact("data", "boxes","packages","technicians"));
        }
        return view("back.imports.queue", compact("data", "boxes","packages","technicians"));
      
        //   $data=  Excel::load($request->file('file'),function($reader) {

        //     print_r($reader->get());

        // });
        //  print_r( $data);
        //->store('temp'));
        // return back()->with("msg", "Import Successfully");
    }
    public function isp_client_import_pppoe_save(Request $request)
    {
        $save = $this->clientService->ispClientImportSave($request,"pppoe");

       // dd($save);
        if($save){
            return redirect()->route('isp-clients')->with("msg", "Import Successfully");
        } else{
            return redirect()->route('isp-clients')->with( "msg", "Import Failed! try again.");
        }
    }
    public function isp_client_import_queue_save(Request $request)
    {
        $save = $this->clientService->ispClientImportSave($request,"queue");

       // dd($save);
        if($save){
            return redirect()->route('isp-clients')->with("msg", "Import Successfully");
        } else{
            return redirect()->route('isp-clients')->with( "msg", "Import Failed! try again.");
        }
    }

  

    public function index()
    {
        
      //  $this->DefaultService->DefalutDataSave(9);
        $companies = IspResellers::query()->where(["company_id" => \Settings::company_id()])->get();
        $sms_templates = SmsTemplates::company()->where("system",2)->get();

        $total_client = DB::table($this->clients)->where(["company_id" => \Settings::company_id()])->count();
        $active_client = DB::table($this->clients)->where([
            "company_id" => \Settings::company_id(),
            "connection_mode" => 1
        ])->count();
        $inactive_client = DB::table($this->clients)->where([
            "company_id" => \Settings::company_id(),
            "connection_mode" => 0
        ])->count();
        $locked_client = DB::table($this->clients)->where([
            "company_id" => \Settings::company_id(),
            "connection_mode" => 2
        ])->count();

        return view("back.clients.isp.list", compact("companies", "sms_templates", "total_client", "active_client", "inactive_client", "locked_client"));

    }

    public function add_pppoe()
    {
        $companies = IspResellers::where("company_id", \Settings::company_id())->get();
        $packages = Packages::query()->where("package_type", "client")->where("company_id", \Settings::company_id())->get();
        $zones = Zones::query()->where("zone_type", 1)->where("company_id", \Settings::company_id())->get();
        $networks = Microtiks::query()->where("company_id", \Settings::company_id())->get();
        $cable_types = CableTypes::query()->get();
        $connectivity_types = DB::table($this->connectivity_types)->get();
        $payment_method = PaymentMethods::query()->where("company_id", \Settings::company_id())->get();
        $boxes = Boxes::query()->where("company_id", \Settings::company_id())->get();
        $client_types = DB::table($this->client_types)->get();
        $technicians = Employees::query()->where("company_id", \Settings::company_id())->get();
        $id_type = IdTypes::query()->where('id', 2)->first()->id;
        $id_prefixs = IdPrefixs::query()->where("company_id", \Settings::company_id())->where('ref_id_type_name', $id_type)->get();
        return view("back.clients.isp.pppoe", compact("id_prefixs", "technicians", "client_types", "boxes", "payment_method", "connectivity_types", "cable_types", "networks", "packages", "zones", "companies"));
    }

    public function add_queue()
    {
        $companies = IspResellers::where("company_id", \Settings::company_id())->get();
        $packages = Packages::query()->where("package_type", "client")->where("company_id", \Settings::company_id())->get();
        $zones = Zones::query()->where("zone_type", 1)->where("company_id", \Settings::company_id())->get();
        $networks = Microtiks::query()->where("company_id", \Settings::company_id())->get();
        $cable_types = CableTypes::query()->get();
        $connectivity_types = DB::table($this->connectivity_types)->get();
        $payment_method = PaymentMethods::query()->where("company_id", \Settings::company_id())->get();
        $boxes = Boxes::query()->where("company_id", \Settings::company_id())->get();
        $client_types = DB::table($this->client_types)->get();
        $technicians = Employees::query()->where("company_id", \Settings::company_id())->get();

        $id_type = IdTypes::query()->where('id', 2)->first()->id;
        $id_prefixs = IdPrefixs::query()->where("company_id", \Settings::company_id())->where('ref_id_type_name', $id_type)->get();
        return view("back.clients.isp.queue", compact("id_prefixs", "technicians", "client_types", "boxes", "payment_method", "connectivity_types", "cable_types", "networks", "packages", "zones", "companies"));
    }

    public function save_client(Request $request)
    {
        $company_id = $request->company_id;
        if ($request->action == 1) {
            $data = array();
            $data["company_id"] = $company_id;
            $data["client_name"] = $request->client_name;
            $data["client_id"] = $request->client_username;
            $data["network_id"] = $request->network_id;
            $data["zone_id"] = $request->zone_id;
            $data["node_id"] = $request->node_id;
            $data["box_id"] = $request->box_id;
            $data["pop_id"] = $request->pop_id;
            $data["package_id"] = $request->package_id;
            $data["billing_responsible"] = $request->billing_responsible;
            $data["company_id"] = $request->company_id;
            $data["payment_dateline"] = $request->payment_dateline;
            $data["termination_date"] = $request->termination_date ? date("Y-m-d", strtotime(str_replace("/", "-", $request->termination_date))) : '';
            $data["billing_date"] = $request->billing_date;
            $data["cell_no"] = $request->cell_no;
            $data["technician_id"] = $request->technician_id;
            $data["payment_id"] = $request->payment_id;
            $data["prefix_id"] = $request->prefix_id;
            $data["permanent_discount"] = $request->permanent_discount;
            $data["alter_cell_no_1"] = $request->alter_cell_no_1;
            $data["alter_cell_no_2"] = $request->alter_cell_no_2;
            $data["alter_cell_no_3"] = $request->alter_cell_no_3;
            $data["alter_cell_no_4"] = $request->alter_cell_no_4;
            $data["address"] = $request->address;
            $data["thana"] = $request->thana;
            $data["join_date"] = date("Y-m-d", strtotime(str_replace("/", "-", $request->join_date)));
            $data["occupation"] = $request->occupation;
            $data["email"] = $request->email;
            $data["nid"] = $request->nid;
            $data["previous_isp"] = $request->previous_isp;
            $data["client_type_id"] = $request->client_type_id;
            $data["connectivity_id"] = $request->connectivity_id;
            if ($request->client == "pppoe") {
                $data["pppoe_username"] = $request->pppoe_username;
                $data["pppoe_password"] = $request->pppoe_password;
            } else {
                $data["ip_address"] = $request->ip_address;
            }

            $data["mac_address"] = $request->mac_address;
            $data["connection_mode"] = $request->connection_mode;
            $data["cable_id"] = $request->cable_id;
            $data["required_cable"] = $request->required_cable;
            $data["payment_alert_sms"] = $request->payment_alert_sms;
            $data["payment_conformation_sms"] = $request->payment_conformation_sms;
            $data["note"] = $request->note;
            $data["olt_interface"] = $request->olt_interface;
            $data["gpon_mac_address"] = $request->gpon_mac_address;
            $data["user_and_fiber_status"] = $request->user_and_fiber_status;
            $data["receive_power"] = $request->receive_power;


           // DB::beginTransaction();
           // try {
                $user = new User();
                $user->name = $data["client_name"];
                $user->company_id = $company_id;
                $user->user_id = $request->client_username;
                $user->email = $request->client_username;
                $user->password = Hash::make($request->client_password);
                $user->is_admin = 0;
                $user->user_type = "client";
                $user->ref_role_id = "0";
                $user->activation_date = $data["join_date"];
                $user->is_active = $data["connection_mode"];
                $user->save();
                $id = $user->id;
                $data["auth_id"] = $id;

                if ($request->hasFile("picture")) {
                    $file = $request->file("picture");
                    $name = $user->user_id . "." . $file->guessClientExtension();
                    $data["picture"] = 'app-assets/images/clients/' . $name;
                    //Image::make($file)->resize(150, 150)->save($data["picture"]);

                    $file->move(public_path('app-assets/images/clients/'), $name);
                }

                $result = Clients::create($data);

                if ($result) {
                    if ($request->payable_amount > 0) {
                        $cl_id = $id;
                        //create bill
                        $receive_date = date("Y-m-d");
                        if ($request->receive_amount > 0) {
                            $receive_date = date("Y-m-d", strtotime(str_replace("/", "-", $request->receive_date)));
                        }
                        $this_year = date("Y", strtotime($receive_date));
                        $this_month = date("m", strtotime($receive_date));
                        $bill_type = 1;
                        $bill_count = 1;
                        $bill_id = $this_year . $this_month . $bill_type . $bill_count . $cl_id;
                        $bill["company_id"] = $company_id;
                        $bill["client_id"] = $cl_id;
                        $bill["client_initial_id"] = $data["client_id"];
                        $bill["bill_id"] = $bill_id;
                        $bill["bill_date"] = date("Y-m-d");
                        $bill["bill_month"] = $this_month;
                        $bill["bill_year"] = $this_year;
                        $bill["bill_type"] = $bill_type;
                        $bill["bill_status"] = 1;
                        $bill["bill_approve"] = 1;
                        $bill["package_id"] = $request->package_id;
                        $bill["connection_charge"] = $request->signup_fee ? $request->signup_fee : 0;
                        $bill["previous_amount"] = $request->previous_bill ? $request->previous_bill : 0;
                        $bill["payable_amount"] = $request->payable_amount;//calculated with previous bill // discount // permanent discount
                        $bill["permanent_discount_amount"] = $request->permanent_discount ? $request->permanent_discount : 0;
                        $bill["discount_amount"] = $request->discount ? $request->discount : 0;
                        $bill["created_at"] = date("Y-m-d H:i");
                        $bill["updated_at"] = date("Y-m-d H:i"); 
                        Bills::insert($bill);
                      //  print_r($request->receive_amount);          
                       // if ($request->receive_amount) {
                            $bill_rcv=new BillReceives();
                            $bill_rcv->particular = "Collection" ;
                            $bill_rcv->client_id = $cl_id ;
                            $bill_rcv->bill_id = uniqid();
                            $bill_rcv->company_id = $bill["company_id"];
                            $bill_rcv->discount_amount = $bill["discount_amount"];
                            $bill_rcv->bill_approve = $bill["bill_approve"];
                            $bill_rcv->receive_date = $receive_date;
                            $bill_rcv->receive_amount = $request->receive_amount;
                            $bill_rcv->receive_by = Auth::user()->id;
                            $bill_rcv->save();                          
                      //  }             
                        //end bill creation
                    }

                    //create welcome sms
                    if (isset($request->welcome_sms) && $request->welcome_sms == 1) {
                        $smsBody = SmsTemplates::query()
                            ->where(['company_id' => \Settings::company_id(), "template_type" => 'welcome', "system" => 1, "temp_status" => 1])
                            ->first();
                        $sms_text = \Settings::akbarDyContent(explode(',', $smsBody->keyword), [explode(' ', $data["client_name"])[0], $data["client_id"], $request->client_password], $smsBody->template_text);

                        $sms_data = array(
                            "to" => $data["cell_no"],
                            "sms_text" => $sms_text,
                            "sms_from" => "isp",
                            "sms_sender" => Auth::user()->name,
                            "sms_type" => "english",
                            "sms_api" => null,
                            "schedule_time" => date("Y-m-d H:i")
                        );
                        app('App\Http\Controllers\SMS\SMSController')->master_save_sms($sms_data);                    
                    }
                }
              //  DB::commit();
          //  } catch (\Exception $e) {
            //    print_r($e);
              //  DB::rollback();
                // something went wrong
          //  }

        } else {
            $clients = Clients::find($request->id);
            $data = array();
            $clients->client_name = $request->client_name;
            $clients->network_id = $request->network_id;
            $clients->zone_id = $request->zone_id;
            $clients->node_id = $request->node_id;
            $clients->box_id = $request->box_id;
            $clients->package_id = $request->package_id;
            $clients->payment_dateline = $request->payment_dateline;
            $clients->termination_date = $request->termination_date ? date("Y-m-d", strtotime(str_replace("/", "-", $request->termination_date))) : '';
            $clients->billing_date = $request->billing_date;
            $clients->cell_no = $request->cell_no;
            $clients->technician_id = $request->technician_id;
            $clients->payment_id = $request->payment_id;
            $clients->prefix_id = $request->prefix_id;
            $clients->permanent_discount = $request->permanent_discount;
            $clients->alter_cell_no_1 = $request->alter_cell_no_1;
            $clients->alter_cell_no_2 = $request->alter_cell_no_2;
            $clients->alter_cell_no_3 = $request->alter_cell_no_3;
            $clients->alter_cell_no_4 = $request->alter_cell_no_4;
            $clients->billing_responsible = $request->billing_responsible;
            $clients->company_id = $request->company_id;
            $clients->address = $request->address;
            $clients->thana = $request->thana;
            $clients->join_date = date("Y-m-d", strtotime(str_replace("/", "-", $request->join_date)));
            $clients->occupation = $request->occupation;
            $clients->email = $request->email;
            $clients->nid = $request->nid;
            $clients->previous_isp = $request->previous_isp;
            $clients->client_type_id = $request->client_type_id;
            $clients->connectivity_id = $request->connectivity_id;
            if ($request->client == "pppoe" && $request->pppoe_password) {
                $clients->pppoe_password = $request->pppoe_password;
            }
            $clients->mac_address = $request->mac_address;
            $clients->gpon_mac_address = $request->gpon_mac_address;
            $clients->connection_mode = $request->connection_mode;
            $clients->required_cable = $request->required_cable;
            $clients->note = $request->note;
            $clients->payment_alert_sms = $request->payment_alert_sms;
            $clients->payment_conformation_sms = $request->payment_conformation_sms;
            $clients->updated_at = date("Y-m-d H:i:s");

            if ($request->hasFile("picture")) {
                $client_data = DB::table($this->clients)->whereId($request->id)->first();
                $client_id = $client_data->client_id;
                $client_pic = $client_data->picture;
                if ($client_pic) {
                    if (file_exists(public_path($client_pic))) {
                        unlink(public_path($client_pic));
                    }
                }
                $file = $request->file("picture");
                $name = $client_id . "." . $file->guessClientExtension();
                $clients->picture = 'app-assets/images/clients/' . $name;
                $file->move(public_path('app-assets/images/clients/'), $name);
            }
            $result = $clients->save();
            // $result = Clients::query()->whereId($request->id)->where("company_id1",$company_id)->update($data);

            //update password
//            if ($result) {
//                if ($request->client_password) {
//                    $auth = DB::table($this->clients)->whereId($request->id)->first();
//                    User::find($auth->auth_id)->update(['password' => Hash::make($request->client_password)]);
//                }
//            }
        }

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function update($id)
    {
        $clients = Clients::find($id);
        $companies = IspResellers::query()->where("company_id", \Settings::company_id())->get();
        $packages = DB::table($this->packages)->where("company_id", \Settings::company_id())->get();
        $zones = DB::table($this->zones)->where("company_id", \Settings::company_id())->get();
        $networks = DB::table($this->networks)->where("company_id", \Settings::company_id())->get();
        $cable_types = DB::table($this->cable_types)->where("company_id", \Settings::company_id())->get();
        $connectivity_types = DB::table($this->connectivity_types)->where("company_id", \Settings::company_id())->get();
        $payment_method = DB::table($this->payment_method)->where("company_id", \Settings::company_id())->get();
        $boxes = DB::table($this->boxes)->where("company_id", \Settings::company_id())->get();
        $client_types = DB::table($this->client_types)->get();
        $technicians = DB::table($this->technicians)->where("company_id", \Settings::company_id())->get();
        return view("back.clients.isp.update", compact("clients", "technicians", "client_types", "boxes", "payment_method", "connectivity_types", "cable_types", "networks", "packages", "zones", "companies"));
    }

    public function clientList(Request $request)
    {

        $company_id = $request->company_id;
        $columns = array(
            0 => $this->clients . '.id',
            1 => 'client_id',
            2 => 'zone_id',
            3 => 'package_id',
            4 => 'join_date',
            5 => 'payment_dateline',
        );

        $status = $request->status;

        if ($status == 'active') {
            $status = [1];
        } elseif ($status == 'inactive') {
            $status = [0];
        } elseif ($status == "locked") {
            $status = [2];
        } else {
            $status = [0, 1, 2];
        }

        $totalData = $totalData = Clients::query()
            ->where("company_id", $company_id)->whereIn("connection_mode", $status)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Clients::query()

                ->whereIn("connection_mode", $status)
                ->where("company_id", $company_id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = trim($request->input('search.value'));

            $posts = Clients::query()

                ->where("company_id", $company_id)
                ->whereIn("connection_mode", $status)
                ->where(function ($q) use ($search) {
                    return $q->where('client_id', 'LIKE', "%{$search}%")
                        ->orWhere('client_name', 'LIKE', "%{$search}%")
                        ->orWhere('cell_no', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Clients::query()
                ->whereIn("connection_mode", $status)
                ->where("clients.company_id", $company_id)
                ->where(function ($q) use ($search) {
                    return $q->where('client_id', 'LIKE', "%{$search}%")
                        ->orWhere('client_name', 'LIKE', "%{$search}%")
                        ->orWhere('cell_no', 'LIKE', "%{$search}%");
                })
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            $i = $start;
            $j = $start + ($limit > count($posts) ? count($posts) : $limit);
            foreach ($posts as $key => $post) {
                $nestedData = array();
                if ($dir == 'desc') {
                    $i++;
                } else {
                    $i = $j--;
                }
                $nestedData[] = $i;
                $nestedData[] = $post->client_id . "<br>" . $post->client_name . "<br>" . $post->cell_no;
                $nestedData[] = $post->zone->zone_name_en . "<br>" . $post->address;
                $nestedData[] = $post->package->package_name . "<br><b style='font-size:20px'>&#2547;</b>" . $post->package->package_price;
                $nestedData[] = $post->join_date;
                $nestedData[] = $post->payment_dateline;
                $lock = '';
                if ($post->connection_mode == 1 or $post->connection_mode == 0) {
                    $lock .= "Lock";
                } else {
                    $lock .= "Unlock";
                }
                $is_locked = $post->connection_mode;
                if ($post->lock_status == 1 && ($post->lock_sms == 1 or $post->lock_sms == 0)) {
                    $is_locked = 20;
                    $lock = "Lock Cancel";
                }
                $status = '<select  class="action_status" data="' . $post->connection_mode . '" is_locked="' . $is_locked . '" id="' . $post->id . '" activeId="' . $post->auth_id . '" details="' . $post->client_id . "-" . $post->client_name . '">
                <option value="1" ' . ($post->connection_mode == 1 ? "selected" : "") . '>Active</option>
                <option value="0" ' . ($post->connection_mode == 0 ? "selected" : "") . '>In-active</option>
                <option value="2"   >' . $lock . '</option>';
                if ($post->connection_mode == 2) {
                    $status .= '<option disabled ' . ($post->connection_mode == 2 ? "selected" : "") . '>Locked</option>';
                }
                $status .= ' </select>';

                $nestedData[] = $status;

                $nestedData[] = '<button  class="btn btn-outline-purple btn-sm dropdown-toggle clientId' . $post->id . '" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               Action </button>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                <a id="' . $post->id . '" class="send_sms dropdown-item text-success" href="#"><span class="ft-message-circle"></span> SMS</a>
                <a id="' . $post->id . '" class="lockCl dropdown-item text-info" href="#" is_locked="' . $is_locked . '"><span class="ft-lock"></span> ' . $lock . '</a>
                <a href="' . route("isp-client-update", $post->id) . '" class="dropdown-item text-primary"><span class="ft-edit"></span> Edit</a>
                <a id="' . $post->client_id . '" class="dropdown-item text-warning dueSMS"><span class="la la-envelope"></span> Due SMS</a>
               </div>';
                //   <a id="'  .$post->client_pk_id. '" class="deleteData dropdown-item text-danger" href="#"><span class="ft-trash"></span> Del</a>

                $data[] = $nestedData;
            }
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "status" => $status
        ];

        echo json_encode($json_data);

    }

    public function clientUpdate(Request $request)
    {
        $result = DB::table($this->clients)->where("id", $request->id)->first();
        if ($result) {
            echo json_encode($result);
        } else {
            echo 0;
        }
    }

    public function clientDelete(Request $request)
    {
        $auth = DB::table($this->clients)->whereId($request->id)->first();
        $auth_id = $auth->auth_id;
        $client_pic = $auth->picture;
        DB::table("users")->whereId($auth_id)->delete();
        $result = DB::table($this->clients)->whereId($request->id)->delete();
        if ($result) {
            if (file_exists(public_path($client_pic))) {
                unlink(public_path($client_pic));
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    public function clientLockedUnlocked(Request $request)
    {
        $client_id = $request->locked_id;
        $is_locked = $request->is_locked;
        $lock_stability = $request->lock_time; //how many times to locked
        $lock_sms = $request->lock_sms_notification == "on" ? 1 : 0;
        $lock_datetime = date("Y-m-d H:i:s");

        $payment_commitment_dateline = null;
        $operation = "";
        //schedule lock
        if ($is_locked == 1 and $lock_sms == 1) {//when client is active
            $lock_status = 1;
            $lock_datetime = date("Y-m-d H:i:s", strtotime("+$lock_stability hours" . $lock_datetime));
            $operation = "sch_lock";
        }
        //quick lock
        if ($is_locked == 1 and $lock_sms == 0) {//when client is active
            $lock_status = 0;
            $is_locked = 2;
            $lock_datetime = null;
            $operation = "quick_lock";
        }
        //schedule lock
        if ($is_locked == 0 and $lock_sms == 1) {//when client is inactive
            $lock_status = 1;
            $lock_datetime = date("Y-m-d H:i:s", strtotime("+$lock_stability hours" . $lock_datetime));
            $operation = "sch_lock";
        }
        //quick lock
        if ($is_locked == 0 and $lock_sms == 0) {//when client is inactive
            $lock_status = 0;
            $is_locked = 2;
            $lock_datetime = null;
            $operation = "quick_lock";
        }
        //quick unlock
        if ($is_locked > 2) {
            $lock_datetime = null;
            $lock_sms = 0;
            $is_locked = 1;
            $lock_status = 0;
            $operation = "sch_lock_cancel";
        }

        $isPaymentPaid = $request->paymentPaid;
        if ($is_locked == 2 and $isPaymentPaid == 1) {
            $is_locked = 1;
            $lock_status = 0;
            $lock_datetime = null;
            $operation = "quick_unlock_with_paid";
        }

        if ($is_locked == 2 and $isPaymentPaid == 0 and $operation != "quick_lock") {
            $lock_status = 1;
            $payment_commitment_dateline = date("Y-m-d 23:59:59", strtotime($request->payment_commitment_date));
            $lock_sms = 0;
            $lock_datetime = $payment_commitment_dateline;
            $operation = "quick_unlock_and_sch_lock";
        }

        $update_data = [
            "connection_mode" => $is_locked,
            "lock_status" => $lock_status,
            "lock_sms" => $lock_sms,
            "lock_datetime" => $lock_datetime,
            "lock_commit_pay_deadline" => $payment_commitment_dateline
        ];
        //        echo $operation;
        // echo json_encode($update_data); exit();
        $result = Clients::whereId($client_id)->update($update_data);
        if ($result) {
            echo $operation;
            //print_r($update_data);
        } else {
            echo 11;//error
        }
    }

    public function clientStatusUpdate(Request $request)
    {
        $client = Clients::find($request->id);
        $client->connection_mode = $request->status;
        $client->save();
        if ($client) {
            return response()->json(["status" => "success"], 200);
        }
    }

    public function clientCount(Request $request)
    {
        $company_id = $request->company_id;
        $active = Clients::query()->where([
            "company_id" => $company_id,
            "connection_mode" => 1
        ])->count();
        $inactive = Clients::query()->where([
            "company_id" => $company_id,
            "connection_mode" => 0
        ])->count();
        $locked = Clients::query()->where([
            "company_id" => $company_id,
            "connection_mode" => 2
        ])->count();
        echo json_encode(
            [
                "active" => $active,
                "inactive" => $inactive,
                "locked" => $locked,
                "company_id" => $company_id,
            ]
        );
    }

    public function last_client_id(Request $request)
    {
        $id_prefix = $request->prefix;

        if ($id_prefix) {
            $prefix = IdPrefixs::query()->whereId($id_prefix)->first();
            $initial_id_digit = $prefix->initial_id_digit;
            $prefix_name = $prefix->id_prefix_name;

            $total_client = Clients::query()->where('company_id', \Settings::company_id())->where("prefix_id", $id_prefix)->count();

            $new_client_id = $initial_id_digit + $total_client + 1;
            $new_client_id = $prefix_name . $new_client_id;

            echo $new_client_id;
        } else {
            echo 0;
        }


    }
}
