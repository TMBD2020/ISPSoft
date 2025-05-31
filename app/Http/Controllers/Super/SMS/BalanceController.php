<?php

namespace App\Http\Controllers\Super\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TmbdUsers;
use App\Models\SmsBalances;

class BalanceController extends Controller
{
   
    public function AddBalance()
    {
        $companies = TmbdUsers::where('approval', '1')->get();
        return view("super.sms.balance.add_balance",compact('companies'));
    }
    function saveBalance(Request $request)
    {
        $sms_qty = $request->new_balance/$request->sms_rate;
        $bal = new SmsBalances();
        $bal->particular = "Recharge";
        $bal->sms_qty = $sms_qty;
        $bal->sms_rate = $request->sms_rate;
        $bal->amount = $request->new_balance;
        $bal->sms_type = $request->sms_type;
        $bal->transaction_type = $request->transaction_type;
        $bal->transaction_id = uniqid();
        $bal->transaction_date = $request->transaction_date;
        $bal->company_id = $request->company_id;
        $bal->note = $request->note;
        $bal->save();
        if($bal){
            $company = TmbdUsers::find($request->company_id);
            $company->sms_balance = $company->sms_balance + $request->new_balance;
            $company->save();
        }

        return response()->json($bal);
    }
}
