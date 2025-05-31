<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\Bills;
use App\Models\PaymentMethods;
use App\Models\Packages;
use DB;
use Carbon\Carbon;
use App\Services\BillService;

class WidgetController extends Controller
{
    protected $billService;

    public function __construct(
        BillService $billService
    ) {
        $this->billService = $billService;
    }
    public function index(Request $request){
        if($request->action=="active"){
            return $this->active($request->id,$request->aid,$request->details);
        }
        if($request->action=="lock"){
           return $this->lock($request->id);
        }
    }
    public function active($id,$aid,$details){
        $clients = Clients::find($id);
        // $due_bills=Bills::query()
        //     ->select(DB::raw("(SUM(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as payable"))
        //     ->where("client_id",$aid)
        //     ->groupBy("client_id")
        //     ->get();
            
        $due = $this->billService->isp_client_current_due($id);
        if ($due < 0) {
            $due = 0;
        }
        $html = '<div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="ModalLabel"><span class="ltitle">Active</span>Client</h4>
                <button type="button" class="close dismiss_status" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ActiveClient" method="post">
                <div class="modal-body">

                    <input type="hidden" name="_token" value="'.csrf_token().'"/>
                    <input type="hidden" name="id2" value="'.$id.'"/>
                    <input type="hidden" name="id" value="'.$aid.'"/>
                    <input type="hidden" id="previous_bill" value="'.$due.'"/>
                    <input type="hidden" name="collected_by" value="'.auth()->user()->id .'"/>
                    <input type="hidden" name="receive_date" value="'.(date("Y/m/d")).'"/>
<h6 class="text-center"> <b>Client</b> : '.$details.',  <b>Due bill</b> : <u>Tk '.$due.'</u></h6><hr/>';

        $packa='';
        $packagePrice=0;
        $packages = Packages::where(["company_id"=>\Settings::company_id(),"package_type"=>"client"])->get();
        foreach ($packages as $pack) {
            if($clients->package_id==$pack->id){
                $packagePrice=$pack->package_price-$clients->permanent_discount;
            }
            $packa.="<option value='".$pack->id."' ".($clients->package_id==$pack->id?'selected':'')." data='".$pack->package_price."'>".$pack->package_name." [".$pack->package_price."]</option>";
        }
$today=date("d");
        $end_of_month       = Carbon::now()->endOfMonth()->toDateString();
        $end_of_month       = date("d",strtotime($end_of_month));
        $days=$end_of_month-$today;
        $day_wise__bill = round($days*$packagePrice/30);//day wise calculate
        $new_bill = ($day_wise__bill+$due);
        $html.='
            <div class="row"  style="margin-top:2px">
                <div class="col-md-6">
                    <label>Package</label>
                    <select class="form-control" onchange="_payableFee()" name="package_id" id="package_id">'.$packa.'</select>
                </div>
                 <div class="col-md-6">
                    <label>Commitment Date</label>
                    <input type="date" class="form-control" name="commitment_date" >
                </div>

             </div>';

       // if($due>0){
            $payme='';
            $payments = PaymentMethods::where("company_id",\Settings::company_id())->get();
            foreach ($payments as $pay) {
                $payme.="<option value='".$pay->id."'>".$pay->payment_name."</option>";
            }
           // if(\Permission::sub_module(19,'write_access')) {

                $html .= '
                <div class="row"  style="margin-top:4px">
                    <div class="col-md-4">
                        <label>Per. Discount</label>
                        <input type="text" class="form-control text-right permanent_discount" onkeyup="_payableFee()" value="'.$clients->permanent_discount.'" name="permanent_discount" >
                    </div>
                    <div class="col-md-4">
                        <label>Discount</label>
                        <input type="text" class="form-control text-right discount_amount" name="discount_amount" onkeyup="_payableFee()">
                    </div>
                      <div class="col-md-4">
                    <label>Payable</label>
                    <input type="text" class="form-control text-right payable_amount"  readonly value="'.$new_bill.'">
                    <input type="hidden" class="dayWiseBill" name="payable" readonly value="'.$day_wise__bill.'">
                </div>

                </div>';
          //  }
            $html.='
            <div class="row"  style="margin-top:2px">
                <div class="col-md-6">
                    <label>Receive</label>
                    <input type="text" class="form-control text-right" name="receive_amount" required>
                </div>
                <div class="col-md-6">
                    <label>Payment</label>
                    <select class="form-control" name="payment_method_id">' . $payme . '</select>
                </div>
            </div>
            <div class="row"  style="margin-top:2px">
                <div class="col-md-12">
                    <label>Discount Note</label>
                    <textarea class="form-control" name="note"></textarea>
                </div>
            </div>';
           // }
            $html.='
            <div class="row"  style="margin-top:2px">

                <div class="col-md-12" style="margin-top:5px">
                    <label> <input type="checkbox" name="send_sms" value="1" checked> SMS</label>
                    <label> <input type="checkbox" name="custom_bill" id="custom_bill" value="1" onchange="_payableFee()"> Generate Full Month Bill</label>
                </div>
            </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn grey btn-secondary dismiss_status" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Active</button>
                </div>
            </form>
        </div>';
        echo $html;
    }



    public function lock($id){
        $html = ' <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="ModalLabel"><span class="ltitle">Locked</span>Client</h4>
                <button type="button" class="close dismiss_status" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="UnlockedLockForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="_token" value="'.csrf_token().'"/>
                    <input type="hidden" value="'.$id.'" name="id2"/>
                    <input type="hidden" id="locked_id" name="locked_id"/>
                    <input type="hidden" id="is_locked" name="is_locked"/>

                    <div id="lockedArea">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <label for="lock_stability"> Time <span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="number" class="form-control text-center" min="1" value="6"
                                           name="lock_time" id="lock_stability" required autocomplete="off">

                                    <div class="input-group-addon btn-info">Hours</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <label for="lock_sms_notification">
                                    <input type="checkbox" name="lock_sms_notification" id="lock_sms_notification">
                                    Sent SMS Notification
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <label id="lockMsg" class="text-danger"></label>
                            </div>
                        </div>
                    </div>
                    <div id="unlockedArea" style="display: none;">
                        <label>Is payment has been paid?
                            <input type="radio" name="paymentPaid" value="1" required> Yes
                            <input type="radio" name="paymentPaid" value="0"> No
                        </label>

                        <div id="inputDate" class="col-sm-12">
                            <label>Commitment Date
                                <input type="date" name="payment_commitment_date" value="{{ date("d/m/Y") }}"
                                       class="form-control  datepicker">
                            </label>
                        </div>
                        <h3 class="text-danger unlockedAreaMsg">Are you sure to unlock this client?</h3>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn grey btn-secondary dismiss_status" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger save">Lock</button>
                </div>
            </form>
        </div>
';
        echo $html;
    }
}
