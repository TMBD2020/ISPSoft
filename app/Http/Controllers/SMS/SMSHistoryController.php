<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use App\Models\SmsHistory;

class SMSHistoryController extends Controller
{

    public function index()
    {
        $today = date("Y-m-d");
        $date_from = date("Y-m-d", strtotime('-7days ' . $today));
        $date_to = $today;
        $sms_history = SmsHistory::query()
            ->where('company_id', \Settings::company_id())
            ->whereBetween(DB::raw("date(sms_schedule_time)"), [$date_from, $date_to])
            ->orderBy("sent_time","desc")
            ->get();
        $sms_status = null;
        return view("back.sms.sms_history", compact("sms_history", "date_from", "date_to", 'sms_status'));
    }
    public function report()
    {
        $today = date("Y-m-d");
        $date_from = date("Y-m-d", strtotime('-7days ' . $today));
        $date_to = $today;
        $sms_history = [];
        $report_type = null;
        return view("back.sms.sms_report", compact("sms_history", "date_from", "date_to", 'report_type'));
    }

    public function search_sms_history(Request $request)
    {
        $sms_status = $request->sms_status;
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));

        $sms_history = SmsHistory::query()
            ->where('company_id', \Settings::company_id())
            ->whereBetween(DB::raw("date(sms_schedule_time)"), [$date_from, $date_to]);
        if ($sms_status) {
            $sms_history = $sms_history->where('sms_status', $sms_status);
        }
        $sms_history = $sms_history->orderByRaw("case when sms_status = 'Pending' then sent_time end desc")->get();
        return view("back.sms.sms_history", compact("sms_history", "date_from", "date_to", 'sms_status'));
    }
    public function search_report(Request $request)
    {
        $report_type = $request->report_type;
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));

        $sms_history = SmsHistory::selectRaw("count(id) sms_count,sum(sms_count) sms_qty, date(sms_schedule_time) sms_schedule_time")->company()->where('sms_status', 'Sent');
        if ($report_type=='Daily') {
            $sms_history = $sms_history->whereBetween(DB::raw("date(sms_schedule_time)"), [$date_from, $date_to])->groupByRaw('date(sms_schedule_time)');
        }else{
            $sms_history = $sms_history->whereBetween(DB::raw("date(sms_schedule_time)"), [$date_from, $date_to])->groupByRaw('year(sms_schedule_time),month(sms_schedule_time)');
        }
        $sms_history = $sms_history->orderBy("sms_schedule_time","asc")->get();
        return view("back.sms.sms_report", compact("sms_history", "date_from", "date_to", 'report_type'));
    }
    public function smsStatusUpdate(Request $request)
    {
        $sms_status = $request->sms_status;
        $sms_id = $request->sms_id;
        if ($sms_id) {
            foreach ($sms_id as $id) {
                $sms = SmsHistory::find($id);
                $sms->sms_status = "Cancel";
                $sms->is_retry =2;
                $sms->save();
            }
        }
        if ($sms_id) {
            return response()->json(1);
        }
        return response()->json(0);

    }

}
