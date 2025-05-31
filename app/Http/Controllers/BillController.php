<?php

namespace App\Http\Controllers;

use App\Models\IspResellers;
use App\Models\Packages;
use App\Models\SmsTemplates;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Bills;
use App\Models\Clients;
use App\Models\IspBillHistorys;
use Illuminate\Foundation\Auth\User;
use App\Models\PaymentMethods;
use App\Models\SmsHistory;
use App\Models\BillReceives;

use App\Services\BillService;
use App\Services\SMSService;
use Instasent\SMSCounter\SMSCounter;
class BillController extends Controller
{

    protected $smsCounter;
    protected $employees = "employees";
    protected $clients = "clients";
    protected $packages = "packages";
    protected $zones = "zones";
    protected $nodes = "nodes";
    protected $bills = "bills";
    protected $sms_history = "sms_history";

    protected $billService;
    protected $smsService;

    public function __construct(
        BillService $billService,
        SMSService $smsService,
        SMSCounter $smsCounter
    ) {
        $this->billService = $billService;
        $this->smsService = $smsService;
        $this->smsCounter = $smsCounter;
    }

    public function testBIll($id)
    {
        $bills = Bills::where("client_id", $id)->get();
        $payments = Bills::where("client_id", $id)->get();
        //        dd($bills);
        $dues_by_month = array();

        foreach ($bills as $invoice) {
            $invoice_date = date('Y-m', strtotime($invoice->created_at)); // Extract year and month from invoice date
            $amount_due = $invoice->payable_amount; // Get the total amount due for this invoice

            if (!isset($dues_by_month[$invoice_date])) {
                $dues_by_month[$invoice_date] = $amount_due; // Initialize the total amount due for this month
            } else {
                $dues_by_month[$invoice_date] += $amount_due; // Add the amount due to the existing total for this month
            }
        }
        $sgs = [];
        foreach ($dues_by_month as $month => $amount_due) {
            $due_by_month = $amount_due;
            foreach ($payments as $payment) {
                $payment_date = date('Y-m', strtotime($payment->created_at)); // Extract year and month from payment date
                $amount_received = $payment->receive_amount; // Get the amount received for this payment
//&& strtotime($payment->created_at) <= strtotime($invoice_date . ' +30 days')

                if ($due_by_month > 0) {
                    $due_by_month -= $amount_received; // Subtract the amount received from the total amount due for this month

                }


            }
            echo "Month: $month, Due: $due_by_month<br>";
        }
        //dd($vars);
    }

    public function index()
    {
        //dd(auth()->user()->can('isp-bill-approve'));
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $nodes = DB::table($this->nodes)->get();
        $payments = PaymentMethods::query()->where(["company_id" => \Settings::company_id()])->get();
        if (Auth::user()->can('collected-by-custom') || Auth::user()->can('set-isp-bill-responsible-person')) {
            $employees = User::query()->whereIn("user_type", ['emp', 'admin'])
                ->where(["company_id" => \Settings::company_id()])
                ->get();
        } else {
            $employees = array();
        }
        return view("back.bill.client_bill", compact("nodes", "payments", "employees", "date_from", "date_to"));
    }

    public function createBill(Request $request)
    {
        if ($request->receive_amount <= 0) {
            return response()->json(false);
        }
        $payable = $request->payable;
        if (!isset($request->discount_amount)) {
            $request->discount_amount = 0;
        }
        // $bills = IspBillHistorys::query()
        //     ->where(
        //         [
        //             "company_id" => \Settings::company_id(),
        //             "client_id" => $request->id,
        //             "bill_status" => 0,
        //             "client_type" => 1
        //         ]
        //     )
        //     ->get();

        $total_amount = $request->receive_amount + $request->discount_amount;//with discount
        $newBill = new BillReceives();
        $newBill->bill_approve = 1;
        // if ($payable == $request->receive_amount) {

        //     $newBill->bill_approve = 1;
        //     foreach ($bills as $bill) {
        //         $bill_amount = $bill->bill_amount - $bill->receive_amount;
        //         if ($total_amount >= $bill_amount) {
        //             $his = [
        //                 "bill_status" => 1,
        //                 "receive_amount" => $bill->bill_amount,
        //                 // "particular"        => "cond1",
        //                 "updated_at" => date("Y-m-d H:i:s")
        //             ];
        //             IspBillHistorys::query()->whereId($bill->id)->update($his);
        //         }
        //         if ($total_amount > 0 and $bill_amount > $total_amount and $bill->receive_amount >= 0) {
        //             $his = [
        //                 "bill_status" => 0,
        //                 "receive_amount" => $bill->receive_amount + $total_amount,
        //                 //"particular"        => "cond2",
        //                 "updated_at" => date("Y-m-d H:i:s")
        //             ];
        //             IspBillHistorys::query()->whereId($bill->id)->update($his);
        //         }
        //         if (($bill_amount - $bill->receive_amount) == $total_amount) {
        //             $his = [
        //                 "bill_status" => 1,
        //                 "receive_amount" => $total_amount,
        //                 //"particular"        => "cond3",
        //                 "updated_at" => date("Y-m-d H:i:s")
        //             ];
        //             IspBillHistorys::query()->whereId($bill->id)->update($his);
        //         }
        //         $total_amount -= $bill_amount;
        //     }
        // }



        $newBill->company_id = \Settings::company_id();
        $newBill->payment_method_id = $request->payment_method_id;
        $newBill->bill_id = uniqid();
        //  $newBill->bill_status = 1;
        $newBill->client_id = $request->id;
        // $newBill->client_initial_id = $request->client_initial_id;
        $newBill->client_type = 1;//isp_client
        $newBill->particular = 'Collection';
        // if(auth()->user()->can('isp-bill-approve')){
        //     $newBill->bill_approve = 1;
        // }
        // if($request->receive_amount<$request->payable){
        //     $newBill->bill_approve = 2;
        // }
        $newBill->receive_amount = $request->receive_amount;
        $newBill->discount_amount = $request->discount_amount;
        $newBill->note = $request->note;
        $newBill->bill_date = date("Y-m-d", strtotime(str_replace("/", "-", $request->receive_date)));
        $newBill->receive_date = date("Y-m-d", strtotime(str_replace("/", "-", $request->receive_date)));
        $newBill->bill_month = date("m", strtotime(str_replace("/", "-", $request->receive_date)));
        $newBill->bill_year = date("Y", strtotime(str_replace("/", "-", $request->receive_date)));
        $newBill->receive_by = auth()->user()->id;
        $newBill->receive_by_name = auth()->user()->name;
        $newBill->save();


        if ($newBill) {
            //create welcome sms
            if (isset($request->payment_confirm_sms)) {
                if ($request->payment_confirm_sms == 1) {
                    $clients = Clients::where("auth_id", $request->id)->first();
                    //$bal=$request->receive_amount;
                    $bal = $request->receive_amount;
                    $due = $payable - ($bal + $request->discount_amount);

                    // if(\Settings::company_id()==1){
                    //     $sms_text = "Hello, Greetings from IMAXQ. We've successfully received your payment ".$bal."tk.";
                    //     if($due){
                    //         $sms_text.="\nStill due is ".$due."tk ,date line is ". date("d, F",strtotime(date("Y-m-d")."+5days "));
                    //     }
                    //     $sms_text.="\nHotline:01841918091";
                    // }
                    // if(\Settings::company_id()==3){
                    //     $sms_text = "Hello, Greetings from SNS. We've successfully received your payment ".$bal."tk.";
                    //     if($due){
                    //         $sms_text.="\nStill due is ".$due."tk ,date line is ". date("d, F",strtotime(date("Y-m-d")."+5days "));
                    //     }
                    //     $sms_text.="\nbkash:01881979100\nSupport:01827360719/01850641807"; 

                    // }
                    $smsBody = SmsTemplates::query()
                        ->where(['company_id' => \Settings::company_id(), "template_type" => 'bill_receive', "system" => 1, "temp_status" => 1])
                        ->first();

                    if ($smsBody) {
                        $sms_text = \Settings::akbarDyContent(explode(',', $smsBody->keyword), [explode(' ', $clients->client_name)[0], $bal, $clients->client_id], $smsBody->template_text);
                        // $sms_api = $this->smsService->defaultApi();
                        $sms_data = array(
                            "to" => $clients->cell_no,
                            "sms_text" => $sms_text,
                            "sms_from" => "isp",
                            "sms_sender" => Auth::user()->name,
                            "schedule_time" => date("Y-m-d H:i:s")
                        );
                        $this->smsService->masterSave($sms_data);
                    }

                }
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    //all due bill
    public function dueBill(Request $request)
    {
        $json_data = $this->billService->dueBill($request);

        echo json_encode($json_data);

    }

    //today bill collection
    public function TodayCollectionList(Request $request)
    {
        $json_data = $this->billService->todayCollectedBill($request);

        echo json_encode($json_data);

    }

    //all collection bill
    public function AllCollectedBill(Request $request)
    {
        $json_data = $this->billService->allCollectedBill($request);

        echo json_encode($json_data);

    }

    //client all bill due/paid
    public function client_all_bill(Request $request)
    {
        $serviceData = $this->billService->clientAllBill($request);

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($serviceData['recordsTotal']),
            "recordsFiltered" => intval($serviceData['recordsFiltered']),
            "data" => $serviceData['data'],
            "q" => $serviceData['query']
        );

        echo json_encode($json_data);

    }

    public function BillInfo(Request $request)
    {
        $client = $request->id;
        $company_id = \Settings::company_id();
        $bill = DB::select("SELECT SUM(debit)-SUM(credit) bal,  q.client_id FROM (

            SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE client_id=$client and company_id=$company_id GROUP BY client_id
            
            UNION ALL 
            
            SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit,client_id FROM bill_receives WHERE client_id=$client and company_id=$company_id GROUP BY client_id

            ) AS q GROUP BY client_id ");
        $client = Clients::where("auth_id", $request->id)->first();
        if ($bill) {
            return response()->json([
                "client_initial_id" => $client->client_id,
                "client_name" => $client->client_name,
                "payable_amount" => $bill[0]->bal,
                "mobile" => $client->cell_no
            ]);
        } else {
            return response()->json(false);
        }
    }

    public function isp_client_bill_history(Request $request)
    {
        $company_id = \Settings::company_id();
        $client_id = $request->id;
        // $bills = Bills::query()->where("client_id", $request->id)->get();
        $bills = DB::select("
        
        SELECT id, debit, credit, IFNULL(discount,0) discount, IFNULL(q.permanent_discount,0) permanent_discount,package_name, package_price,particular,bill_date,receive_by,ttype FROM (  
        
            SELECT b.id,payable_amount debit, 0 credit,0 discount,permanent_discount_amount permanent_discount,client_id, p.package_name, p.package_price,b.particular, b.bill_date, '' receive_by,'debit' ttype FROM bills b
            LEFT JOIN packages p ON p.id=b.package_id AND p.company_id= $company_id WHERE b.company_id = $company_id and b.client_id=$client_id 
            
            UNION ALL  
            
            SELECT r.id,0 debit , receive_amount credit, discount_amount discount,0 permanent_discount,client_id,'' package_name, '' package_price, particular,bill_date, u.name receive_by,'credit' ttype FROM bill_receives r INNER JOIN users u ON u.id= r.receive_by AND u.company_id = $company_id
            WHERE r.company_id = $company_id and r.client_id=$client_id 
            
            ) AS q ORDER BY bill_date asc
        ");

        return response()->json(view("back.bill.isp_bill_history", compact('bills'))->render());
    }


    public function isp_client_bill_print(Request $request)
    {
        $company_id = \Settings::company_id();
        if ($request->ttype == "debit") {
            $bills = Bills::query()->where("id", $request->id)->first();
        } else {
            $bills = BillReceives::query()->where("id", $request->id)->first();
        }

        $due = $this->billService->isp_client_current_due($bills->client_id);
        if ($due < 0) {
            $due = 0;
        }
        return view("back.bill.isp_bill_print", compact('bills', 'due'));
    }


    public function isp_client_bill_era(Request $request)
    {
        $result = Bills::find($request->ids);
        if ($result) {
            if ($his = IspBillHistorys::where('bill_id', $result->bill_id)->first()) {
                $his->delete();

            } else {
                if ($result->receive_amount > 0) {
                    $hiss = IspBillHistorys::where('client_id', $result->client_id)->orderBy("id", "desc")->get();
                    $bi = $result->receive_amount;

                    foreach ($hiss as $b) {
                        if ($bi > 0) {
                            if ($bi > $b->receive_amount) {
                                IspBillHistorys::whereId($b->id)
                                    ->update(["receive_amount" => 0, "bill_status" => 0]);
                            }
                            if ($b->receive_amount > $bi) {
                                IspBillHistorys::whereId($b->id)
                                    ->update(["receive_amount" => $b->receive_amount - $bi, "bill_status" => 0]);

                            }

                            $bi -= $b->receive_amount;
                        }
                    }
                    //  echo json_encode($bi);
                }


            }
            if ($result->delete()) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }
    public function isp_client_due_sms(Request $request)
    {
        $company_id = \Settings::company_id();
        $client = $request->id;

        $clients = Clients::where("auth_id", $client)->company()->first();

        $due = $this->billService->isp_client_current_due($client);
        if ($due < 0) {
            $due = 0;
        }
        return response()->json(["status" => true, "sms" => "Dear customer please pay your dues Tk" . $due, "sent_to" => $clients->cell_no, "main" => $due]);

    }

    public function isp_client_due_sms_save(Request $request)
    {
        $sms_data = array(
            "to" => $request->sent_to,
            "sms_text" => $request->sms_text,
            "sms_from" => "isp",
            "sms_sender" => Auth::user()->name,
            "schedule_time" => date("Y-m-d H:i")
        );
        $this->smsService->masterSave($sms_data);
        echo 1;
    }
    public function isp_commitment_date_update(Request $request)
    {
        $client = Clients::query()->company()->where("auth_id", $request->client_id)->first();
        if ($client) {
            $client->termination_date = $request->commitment_date;
            if ($client->save()) {
                $smsText = "Dear " . $client->client_name . ", your commitment date is " . (date("d F,Y", strtotime($request->commitment_date))) . ". Please contact & pay due bill As soon as possible.01993678660(bkash/nagad) ref:ID/Name.\nHotline:01841918091";

                $this->smsService->masterSave(array(
                    "to" => $client->cell_no,
                    "sms_text" => $smsText,
                    "sms_from" => "isp",
                    "sms_sender" => Auth::user()->name,
                    "sms_api" => null,
                    "schedule_time" => date("Y-m-d H:i")
                ));
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }



    public function generate_bill()
    {
        $companies = IspResellers::where("company_id", \Settings::company_id())->get();
        return view("back.bill.generate_client_bill", compact("companies"));
    }
    public function GenerateIspClientPreivew(Request $request)
    {
        $dateString = date('ym'); //Generate a datestring.     
        $client_type = 1;//isp

        $clients = Clients::query()
            ->where("connection_mode", 1)
            ->where("company_id", $request->company_id)
            ->get();

        $today = date("Y-m-d");
        $this_month = date("m");
        $this_year = date("Y");
        $this_month_text = date("M");

        $isp_bills = [];

        foreach ($clients as $client) {
            $bills = [];
            $bill_type = 1;//package bill/monthly bill

            $bill_exist = Bills::query()
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('company_id', $request->company_id)
                ->where('client_id', $client->auth_id)
                ->where('client_type', $client_type)
                ->count();
            $bill_count = $bill_exist + 1;

            $bill_id = $dateString . $bill_type . $client_type . $bill_count . $client->id;

            if ($bill_exist == 0) {
                $bills['particular'] = 'Monthly Bill';
                $bills['company_id'] = $request->company_id;
                $bills['client_id'] = $client->auth_id;
                $bills['client_initial_id'] = $client->client_id;
                $bills['name'] = $client->client_name;
                $bills['cell_no'] = $client->cell_no;
                $bills['bill_id'] = $bill_id;
                $bills['bill_date'] = $today;
                $bills['bill_month'] = $this_month;
                $bills['bill_year'] = $this_year;
                $bills['bill_type'] = $bill_type;
                $bills['bill_approve'] = 1;
                $bills['bill_status'] = 0;
                $bills['package_title'] = $client->package->package_name;
                $bills['package_id'] = $client->package->id;
                $bills['package_amount'] = $client->package->package_price;
                $bills['payable_amount'] = $client->package->package_price - $client->permanent_discount;
                $bills['permanent_discount_amount'] = $client->permanent_discount;
                $bills['payment_alert_sms'] = $client->payment_alert_sms;

                $bills['client_due'] = $client_due = $this->billService->isp_client_current_due($client->auth_id);

                $name = explode(" ", $client->client_name)[0];
                $cell_no = str_replace("+", "", $client->cell_no);

                $smsBody = SmsTemplates::query()
                    ->where(['company_id' => \Settings::company_id(), "template_type" => 'bill_generate', "system" => 1, "temp_status" => 1])
                    ->first();

                if ($smsBody) {
                    $bills['sms_text'] = $sms_text = \Settings::akbarDyContent(explode(',', $smsBody->keyword), [$name, $client->client_id, $this_month_text, $bills['payable_amount'], $this->ordinal_suffix($client->payment_dateline), ($bills['payable_amount'] + $client_due)], $smsBody->template_text);
                    $bills['sms_count'] = $this->smsCounter->count($sms_text)->messages;
                }
                $isp_bills[] = $bills;
            }

        }
        return view('back.bill.generate_isp_preview', compact('isp_bills'))->render();
    }

    public function GenerateIspClientBill(Request $request)
    {
        try{
            if ($request->particular) {
                foreach ($request->particular as $i => $row) {
                    $bills = new Bills();
                    $bills->particular = $row;
                    $bills->company_id = $request->company_id[$i];
                    $bills->client_id = $request->client_id[$i];
                    $bills->client_initial_id = $request->client_initial_id[$i];
                    $bills->bill_id = $request->bill_id[$i];
                    $bills->bill_date = $request->bill_date[$i];
                    $bills->bill_month = $request->bill_month[$i];
                    $bills->bill_year = $request->bill_year[$i];
                    $bills->bill_type = $request->bill_type[$i];
                    $bills->bill_approve = $request->bill_approve[$i];
                    $bills->bill_status = $request->bill_status[$i];
                    $bills->package_title = $request->package_title[$i];
                    $bills->package_id = $request->package_id[$i];
                    $bills->package_amount = $request->package_amount[$i];
                    $bills->payable_amount = $request->payable_amount[$i];
                    $bills->permanent_discount_amount = $request->permanent_discount[$i];
                    $billSaveResult = $bills->save();
                    if ($billSaveResult) {
                        if ($request->payment_alert_sms[$i] == 1 && $request->payment_confirm_sms == 1) {
                            $cell_no = str_replace("+", "", $request->cell_no[$i]);
                            $sms_text = $request->sms_text[$i];
                            $sms_count = $this->smsCounter->count($sms_text)->messages;
                            $sms_type = $this->smsCounter->count($sms_text)->encoding;
    
                            $created_at = date("Y-m-d H:i");
                            $smsData = new SmsHistory();
                            $smsData->company_id = $request->company_id[$i];
                            $smsData->sms_receiver = $cell_no;
                            $smsData->sms_sender = "Administrator";
                            $smsData->sms_count = $sms_count;
                            $smsData->sms_type = $sms_type;
                            $smsData->sms_text = $sms_text;
                            $smsData->is_retry = 1;
                            $smsData->client_type = 'isp';
                            $smsData->sms_subject = "bill_generate";
                            $smsData->sms_status = 'Pending';
                            $smsData->sms_schedule_time = $created_at;
                            $smsData->sms_api = $this->smsService->getAPIid();
                            $smsData->sent_time = $created_at;
                            $smsData->save();
                        }
                    }
                }
                return response()->json(['status' => 'success']);
            }
        }catch (\Exception $e){
           // echo ($e->getMessage());
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }        
    }
    public function OldGenerateIspClientBill(Request $request)
    {
        $dateString = date('ym'); //Generate a datestring.     
        $client_type = 1;//isp

        $clients = Clients::query()
            ->where("connection_mode", 1)
            ->where("company_id", $request->company_id)
            ->get();

        $today = date("Y-m-d");
        $this_month = date("m");
        $this_year = date("Y");
        $this_month_text = date("M");
        $total_client = count($clients);
        $bill_counting = 0;
        foreach ($clients as $client) {
            $bill_type = 1;//package bill/monthly bill

            $bill_exist = Bills::query()
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('company_id', $request->company_id)
                ->where('client_id', $client->auth_id)
                ->where('client_type', $client_type)
                ->count();
            // dd($bill_exist);
            $bill_count = $bill_exist + 1;

            $bill_id = $dateString . $bill_type . $client_type . $bill_count . $client->id;


            //
            //return response()->json($bill_exist);
            if ($bill_exist == 0) {
                $new_data = array();
                $bills = new Bills();
                $bills->particular = 'Monthly Bill';
                $bills->company_id = $request->company_id;
                $bills->client_id = $client->auth_id;
                $bills->client_initial_id = $client->client_id;
                $bills->bill_id = $bill_id;
                $bills->bill_date = $today;
                $bills->bill_month = $this_month;
                $bills->bill_year = $this_year;
                $bills->bill_type = $bill_type;
                $bills->bill_approve = 1;
                $bills->bill_status = 0;
                $bills->package_title = $client->package->package_name;
                $bills->package_id = $client->package->id;
                $bills->package_amount = $client->package->package_price;
                $bills->payable_amount = $client->package->package_price - $client->permanent_discount;
                $bills->permanent_discount_amount = $client->permanent_discount;
                $billSaveResult = $bills->save();

                if ($billSaveResult) {
                    $history = new IspBillHistorys();
                    $history->particular = "Monthly bill";
                    $history->company_id = $request->company_id;
                    $history->client_id = $bills->client_id;
                    $history->bill_id = $bills->bill_id;
                    $history->bill_year = $bills->bill_year;
                    $history->bill_month = $bills->bill_month;
                    $history->bill_amount = $bills->payable_amount;
                    $history->receive_amount = 0;
                    $historySaveReulst = $history->save();

                    if ($historySaveReulst) {
                        $bill_counting++;
                        if ($client->payment_alert_sms == 1 && $request->payment_confirm_sms == 1) {
                            $dueBill = Bills::query()->select(DB::raw('(SUM(payable_amount)-SUM(receive_amount)) AS payable'))
                                ->where("client_id", $client->auth_id)
                                ->where("client_type", $client_type)
                                ->get();
                            if (count($dueBill) > 0) {
                                $client_due = $dueBill[0]->payable;
                            } else {
                                $client_due = 0;
                            }

                            // $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
                            $name = explode(" ", $client->client_name)[0];
                            //                            $sms_text = "Dear ".$name ." (".$client->client_id."), the bill for the month of ".$this_month_text." has been generated as ".$bills->payable_amount."tk.
//                            Unless payment is not confirmed the link will automatically expire on ".$this->ordinal_suffix($client->payment_dateline)." ".$this_month_text.". Total due ".($bills->payable_amount+$client_due)."tk.
//                            Pay your bill today (Bkash/Cash: 01993678660) Call: 01841918091";
                            //echo $sms_text = "Dear ".$client->client_name." (".$client->client_id."), your last month bill ".$new_data["payable_amount"]."tk. has been due.".$client_due;
                            $cell_no = str_replace("+", "", $client->cell_no);

                            $smsBody = SmsTemplates::query()
                                ->where(['company_id' => \Settings::company_id(), "template_type" => 'bill_generate', "system" => 1, "temp_status" => 1])
                                ->first();

                            if ($smsBody) {
                                $sms_text = \Settings::akbarDyContent(explode(',', $smsBody->keyword), [$name, $client->client_id, $this_month_text, $bills->payable_amount, $this->ordinal_suffix($client->payment_dateline), ($bills->payable_amount + $client_due)], $smsBody->template_text);
                                $sms_count = $this->smsCounter->count($sms_text)->messages;
                                $sms_type = $this->smsCounter->count($sms_text)->encoding;
                                if (strlen($cell_no) == 11) {
                                    $cell_no = "88" . $cell_no;
                                    if (strlen($cell_no) == 13) {
                                        $sms_status = "Pending";
                                        $is_retry = 1;
                                    } else {
                                        $sms_status = "Receiver Error";
                                        $is_retry = 0;
                                    }
                                }
                                $created_at = date("Y-m-d H:i");
                                $smsData = new SmsHistory();
                                $smsData->company_id = $request->company_id;
                                $smsData->sms_receiver = $cell_no;
                                $smsData->sms_sender = "Administrator";
                                $smsData->sms_count = $sms_count;
                                $smsData->sms_type = $sms_type;
                                $smsData->sms_text = $sms_text;
                                $smsData->is_retry = $is_retry;
                                $smsData->sms_status = $sms_status;
                                $smsData->sms_schedule_time = $created_at;
                                $smsData->sms_api = $this->smsService->getAPIid();
                                $smsData->sent_time = $created_at;
                                $smsData->save();
                            }
                        }
                    }
                }
            }
        }
        // if ($total_client == $bill_counting) {
        //     return response()->json(['status' => 'success']);
        // }
    }

    public function bill_approval()
    {
        $client_bills = Bills::query()
            ->where('bill_approve', 2)
            ->where('receive_amount', '>', 0)
            ->where("company_id", \Settings::company_id())
            ->orderBy('receive_date', 'desc')
            ->get();
        return view("back.bill.bill_approval", compact('client_bills'));
    }

    public function save_bill_approve(Request $request)
    {

        $bills = Bills::find($request->id);
        $bills->bill_approve = 1;

        $bills->save();



        $billsHis = IspBillHistorys::query()
            ->where(
                [
                    "company_id" => \Settings::company_id(),
                    "client_id" => $bills->client_id,
                    "bill_status" => 0,
                    //"bill_type"   => 'client'
                ]
            )
            ->get();


        $total_amount = $bills->receive_amount + $bills->discount_amount;//with discount

        foreach ($billsHis as $bill) {
            $bill_amount = $bill->bill_amount - $bill->receive_amount;
            if ($total_amount >= $bill_amount) {
                $his = [
                    "bill_status" => 1,
                    "receive_amount" => $bill->bill_amount,
                    // "particular"        => "cond1",
                    "updated_at" => date("Y-m-d H:i:s")
                ];
                IspBillHistorys::query()->whereId($bill->id)->update($his);
            }
            if ($total_amount > 0 and $bill_amount > $total_amount and $bill->receive_amount >= 0) {
                $his = [
                    "bill_status" => 0,
                    "receive_amount" => $bill->receive_amount + $total_amount,
                    //"particular"        => "cond2",
                    "updated_at" => date("Y-m-d H:i:s")
                ];
                IspBillHistorys::query()->whereId($bill->id)->update($his);
            }
            if (($bill_amount - $bill->receive_amount) == $total_amount) {
                $his = [
                    "bill_status" => 1,
                    "receive_amount" => $total_amount,
                    //"particular"        => "cond3",
                    "updated_at" => date("Y-m-d H:i:s")
                ];
                IspBillHistorys::query()->whereId($bill->id)->update($his);
            }
            $total_amount -= $bill_amount;
        }
        return response()->json(['msg' => 'success']);
    }

    function ordinal_suffix($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            $abbreviation = $number . 'th';
        } else {
            $abbreviation = $number . $ends[$number % 10];
        }
        return $abbreviation;
    }


    public function createReActiveClientBill(Request $request)
    {
        $result = $this->billService->generateReactiveClientBill($request);

        echo $result;
    }

    public function create_client_bill_other(Request $request)
    {
        $dateString = date('ym');
        $client_type = 1;//isp
        $company_id = \Settings::company_id();

        $today = date("Y-m-d", strtotime(str_replace("/", "-", $request->bill_date)));
        $this_month = date("m", strtotime($today));
        $this_year = date("Y", strtotime($today));
        $this_month_text = date("M", strtotime($today));

        $bill_type = 2;//other bill
        $bill_exist = Bills::query()
            ->where('bill_type', $bill_type)
            ->where('bill_month', $this_month)
            ->where('bill_year', $this_year)
            ->where('company_id', $company_id)
            ->where('client_id', $request->id)
            ->where('client_type', $client_type)
            ->count();
        $bill_count = $bill_exist + 1;
        $bill_id = $dateString . $bill_type . $client_type . $bill_count . $request->id;

        if ($request->bill_amount > 0) {
            $bills = new Bills();
            $bills->particular = $request->particular;
            $bills->company_id = $company_id;
            $bills->client_id = $request->id;
            $bills->client_initial_id = $request->client_initial_id;
            $bills->bill_id = $bill_id;
            $bills->bill_date = $today;
            $bills->bill_month = $this_month;
            $bills->bill_year = $this_year;
            $bills->bill_type = $bill_type;
            $bills->bill_approve = 1;
            $bills->bill_status = 0;
            $bills->payable_amount = $request->bill_amount;
            $bills->discount_amount = $request->discount_amount;
            $bills->note = $request->note;
            $bills->permanent_discount_amount = 0;
            $billSaveResult = $bills->save();
            if ($billSaveResult) {
                $history = new IspBillHistorys();
                $history->particular = $bills->particular;
                $history->company_id = $bills->company_id;
                $history->client_id = $bills->client_id;
                $history->bill_id = $bills->bill_id;
                $history->bill_year = $bills->bill_year;
                $history->bill_month = $bills->bill_month;
                $history->bill_amount = $bills->payable_amount;
                $history->receive_amount = 0;
                $historySaveReulst = $history->save();
                if ($historySaveReulst) {
                    return response()->json(['status' => 'success']);
                }
            }
            return response()->json(['status' => 'error']);
        }
    }

    public function client_bill_mobile_update(Request $request)
    {
        $client = Clients::where("auth_id", $request->id)->first();
        if ($client && $request->new_mobile) {
            if ($request->set_as == "primary") {
                $client->cell_no = $request->new_mobile;
            } else {
                $client->alter_cell_no_4 = $request->new_mobile;
            }
            if ($client->save()) {
                return response()->json(true);
            }
            return response()->json(false);
        }
        return response()->json(false);
    }

    //get responsible person;
    public function getBillResponsiblePerson(Request $request)
    {
        $client = Clients::query()
            ->select(DB::raw("billing_responsible,auth_id,client_id,client_name,zone_id,pop_id,node_id,box_id"))
            ->where("auth_id", $request->id)
            ->first();
        if ($client) {
            return response()->json([
                "responsible_person" => $client->billing_responsible,
                "client_initial_id" => $client->client_id,
                "client_name" => "Client: " . $client->client_id . " - " . $client->client_name
                    . "<br> POP: " . $client->pop->pop_name
                    . "<br> Zone: " . $client->zone->zone_name_en
                    . "<br> Node: " . $client->node->node_name
                    . "<br> Sub Node: " . $client->box->box_name
                ,
                "client_id" => $client->auth_id
            ]);
        } else {
            return response()->json(false);
        }
    }
    public function saveBillResponsiblePerson(Request $request)
    {
        if ($request->billing_responsible) {
            $client = Clients::query()->where("auth_id", $request->client_id)->first();
            $client->billing_responsible = $request->billing_responsible;
            if ($client->save()) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        } else {
            return response()->json(false);
        }

    }
}
