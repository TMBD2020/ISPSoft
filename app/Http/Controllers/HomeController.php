<?php

namespace App\Http\Controllers;

use App\Models\BillReceives;
use Illuminate\Http\Request;
use App\Models\CatbClients;
use App\Models\Bills;
use App\Models\AccessPermissions;
use DB;
use Auth;
use App\Services\GraphService;

class HomeController extends Controller
{
    protected $clients = "clients";
    protected $tickets = "tickets";
    protected $expenses = "expenses";
    protected $bills = "bills";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
// dd(auth()->user()->admin->approval);
        if (auth()->user()->user_type == "super") {
            return view('layouts.dashboard.super');
        }

        //    $dashboardAccess = AccessPermissions::query()
        //        ->select("read_access","sub_module_id")
        //         ->where(
        //             [
        //                 "role_id"=>auth()->user()->ref_role_id,
        //                 "read_access"=>1
        //             ]
        //         )->whereIn("sub_module_id",[48,49])->get();//dashboard isp & catv
        $dashboardAccess = [];
        if (auth()->user()->user_type == "emp") {
            return view('layouts.dashboard.employee', compact("dashboardAccess"));
        }
        if (auth()->user()->user_type == "client") {
            return view('layouts.dashboard.client', compact("dashboardAccess"));

        }
        if (auth()->user()->user_type == "reseller") {

            return view('layouts.dashboard.reseller', compact("dashboardAccess"));
        }
        if (auth()->user()->user_type == "admin") {
            return view('layouts.dashboard.admin', compact("dashboardAccess"));
        }
    }

    public function dashboard(Request $request)
    {

        $company_id = \Settings::company_id();




        $today = date("Y-m-d");

        if (auth()->user()->user_type == "client") {
            return $this->client_dashboard();
        }

        $isp_total_client = DB::table($this->clients)->where("company_id", $company_id)->count();
        $isp_active_client = DB::table($this->clients)->where("company_id", $company_id)->where("connection_mode", 1)->count();
        $isp_inactive_client = DB::table($this->clients)->where("company_id", $company_id)->where("connection_mode", 0)->count();
        $isp_new_client = DB::table($this->clients)->where("company_id", $company_id)->where(DB::raw("date(created_at)"), $today)->count();
        $isp_open_tickets = DB::table($this->tickets)->where("company_id", $company_id)->where(DB::raw("date(created_at)"), $today)->where("ticket_status", 1)->count();

        $catv_total_client = CatbClients::all()->where("company_id", $company_id)->count();
        $catv_active_client = CatbClients::query()->where("company_id", $company_id)->where("connection_mode", 1)->count();
        $catv_inactive_client = CatbClients::query()->where("company_id", $company_id)->where("connection_mode", 0)->count();
        $catv_new_client = CatbClients::query()->where("company_id", $company_id)->where(DB::raw("date(created_at)"), $today)->count();
        $catv_open_tickets = 0;

        $present_year_month = date("Y-m");
        $present_month = explode("-", $present_year_month)[1];
        $present_year = explode("-", $present_year_month)[0];

        $previous_year_month = date("Y-m", strtotime("-1 month " . $today));
        $previous_month = explode("-", $previous_year_month)[1];
        $previous_year = explode("-", $previous_year_month)[0];

        $expenses = DB::table($this->expenses)->select(DB::raw("sum(expense_amount) as expense_amount"))
            ->where("company_id", $company_id)
            ->where(DB::raw("expense_date"), $today)
            ->first()->expense_amount;

        //previous collection
        $isp_previous_bills = BillReceives::company()
            ->select(DB::raw("sum(receive_amount) as bill"))
            ->where([ "bill_month" => $previous_month, "bill_year" => $previous_year]);
        if (!in_array(auth()->user()->ref_role_id, [1, 2])) {
            $isp_previous_bills = $isp_previous_bills->where(DB::raw("receive_by"), auth()->user()->id);
        }
        $isp_previous_bills = $isp_previous_bills
            //->where(DB::raw("receive_date"),$today)
            ->first()->bill;

        //present collection
        $isp_present_bills = Bills::query()
            ->select(DB::raw("sum(receive_amount) as bill"))
            ->where(["client_type" => 1, "company_id" => $company_id, "bill_month" => $present_month, "bill_year" => $present_year]);
        if (!in_array(auth()->user()->ref_role_id, [1, 2])) {
            $isp_present_bills = $isp_present_bills->where(DB::raw("receive_by"), auth()->user()->id);
        }
        $isp_present_bills = $isp_present_bills
            //->where(DB::raw("receive_date"),$today)
            ->first()->bill;

        $catv_previous_bills = Bills::query()
            ->select(DB::raw("sum(receive_amount) as bill"))
            ->where(["company_id" => $company_id, "client_type" => 2, "bill_month" => $previous_month, "bill_year" => $previous_year]);

        if (!in_array(auth()->user()->ref_role_id, [1, 2])) {
            $catv_previous_bills = $catv_previous_bills->where(DB::raw("receive_by"), auth()->user()->id);
        }
        $catv_previous_bills = $catv_previous_bills->first()->bill;

        $catv_present_bills = Bills::query()
            ->select(DB::raw("sum(receive_amount) as bill"))
            ->where(["client_type" => 2, "company_id" => $company_id, "bill_month" => $present_month, "bill_year" => $present_year]);
        if (!in_array(auth()->user()->ref_role_id, [1, 2])) {
            $catv_present_bills = $catv_present_bills->where(DB::raw("receive_by"), auth()->user()->id);
        }
        $catv_present_bills = $catv_present_bills
            //->where(DB::raw("receive_date"),$today)
            ->first()->bill;

        $graph = new GraphService();
        $graph_payment = $graph->paymentMethod();

        return response()->json(
            [
                "isp_total_client" => $isp_total_client,
                "isp_active_client" => $isp_active_client,
                "isp_inactive_client" => $isp_inactive_client,
                "isp_new_client" => $isp_new_client,
                "isp_open_tickets" => $isp_open_tickets,

                "catv_total_client" => $catv_total_client,
                "catv_active_client" => $catv_active_client,
                "catv_inactive_client" => $catv_inactive_client,
                "catv_new_client" => $catv_new_client,
                "catv_open_tickets" => $catv_open_tickets,

                "expenses" => $expenses,
                "previous_isp_bills" => $isp_previous_bills,
                "isp_present_bills" => $isp_present_bills,

                "previous_catv_bills" => $catv_previous_bills,
                "catv_present_bills" => $catv_present_bills,

                "month" => $previous_month,
                "graph_payment" => $graph_payment,
            ]
        );
    }
    public function dashboard2()
    {

        $company_id = 1;

        $today = date("Y-m-d");
        $isp_total_client = DB::table($this->clients)->where("company_id", $company_id)->count();
        $isp_active_client = DB::table($this->clients)->where("company_id", $company_id)->where("connection_mode", 1)->count();
        $isp_inactive_client = DB::table($this->clients)->where("company_id", $company_id)->where("connection_mode", 0)->count();
        $isp_new_client = DB::table($this->clients)->where("company_id", $company_id)->where(DB::raw("date(created_at)"), $today)->count();
        $isp_open_tickets = DB::table($this->tickets)->where("company_id", $company_id)->where(DB::raw("date(created_at)"), $today)->where("ticket_status", 1)->count();

        $catv_total_client = CatbClients::all()->where("company_id", $company_id)->count();
        $catv_active_client = CatbClients::query()->where("company_id", $company_id)->where("connection_mode", 1)->count();
        $catv_inactive_client = CatbClients::query()->where("company_id", $company_id)->where("connection_mode", 0)->count();
        $catv_new_client = CatbClients::query()->where("company_id", $company_id)->where(DB::raw("date(created_at)"), $today)->count();
        $catv_open_tickets = 0;


        $expenses = DB::table($this->expenses)->select(DB::raw("sum(expense_amount) as expense_amount"))
            ->where("company_id", $company_id)
            ->where(DB::raw("expense_date"), $today)
            ->first()->expense_amount;
        $isp_bills = DB::table($this->bills)
            ->select(DB::raw("sum(receive_amount) as bill"))
            ->where("company_id", $company_id)
            ->where(DB::raw("receive_date"), $today)
            ->first()->bill;
        $catv_bills = CatbBills::query()
            ->select(DB::raw("sum(receive_amount) as bill"))->where("company_id", $company_id)
            ->where(DB::raw("receive_date"), $today)->first()->bill;

        echo json_encode(
            [
                "isp_total_client" => $isp_total_client,
                "isp_active_client" => $isp_active_client,
                "isp_inactive_client" => $isp_inactive_client,
                "isp_new_client" => $isp_new_client,
                "isp_open_tickets" => $isp_open_tickets,

                "catv_total_client" => $catv_total_client,
                "catv_active_client" => $catv_active_client,
                "catv_inactive_client" => $catv_inactive_client,
                "catv_new_client" => $catv_new_client,
                "catv_open_tickets" => $catv_open_tickets,

                "expenses" => $expenses,
                "isp_bills" => $isp_bills,
                "catv_bills" => $catv_bills
            ]
        );
    }

    public function client_dashboard()
    {

        $company_id = auth()->user()->company_id;

        $today = date("Y-m-d");
        $dueBill = DB::table($this->bills)
            ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as bill"))
            ->where(["company_id" => $company_id])
            ->where(DB::raw("client_id"), auth()->user()->id)
            ->first()->bill;
        $receive_amount = DB::table($this->bills)
            ->select(DB::raw("receive_amount"))
            ->where(["company_id" => $company_id])
            ->where(["client_id" => auth()->user()->id])
            ->where("receive_amount", ">", 0)
            ->latest('receive_date')->first()->receive_amount;



        echo json_encode(["total_due" => $dueBill, "receive_amount" => $receive_amount]);
    }
}
