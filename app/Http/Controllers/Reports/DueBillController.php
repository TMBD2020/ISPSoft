<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Bills;
use App\Models\Clients;
use App\Models\Packages;
use App\Models\Zones;
use Illuminate\Support\Facades\Storage;
use Mpdf\Output\Destination;
use PDF;
use App\Models\BillReceives;
class DueBillController extends Controller
{

    protected $bills = "bills";
    protected $clients = "clients";
    protected $packages = "packages";
    protected $employees = "employees";
    protected $zones = "zones";

    public function index()
    {
        $due_bills = [];

        $employees = DB::table($this->employees)->where("company_id", \Settings::company_id())->get();
        $zones = Zones::query()->where("zone_type", 1)->where("company_id", \Settings::company_id())->get();
        $zone_id = null;
        $client_status = null;
        return view("back.reports.due.due_bill", compact("client_status", "due_bills", "zones", "employees", "zone_id"));
    }


    public function filterDueBill(Request $request)
    {

        $zone_id = $request->zone_id;
        $client_status = $request->client_status;
        $company_id = \Settings::company_id();

        $sql = "        
                SELECT sum(debit)-sum(credit) payable, q.client_id, c.address, c.note,c.termination_date,c.client_id username, c.client_name,c.cell_no, c.connection_mode, c.billing_responsible,p.package_name, p.package_price,z.zone_name_en FROM (

                    SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE company_id =$company_id  GROUP BY client_id

                    UNION ALL 

                    SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit, client_id FROM bill_receives WHERE company_id = $company_id  GROUP BY client_id

                ) AS q 
                INNER JOIN clients c ON c.auth_id=q.client_id AND c.company_id=$company_id
                LEFT JOIN packages p ON p.id=c.package_id AND p.company_id=$company_id 
                LEFT JOIN zones z ON z.id=c.zone_id AND z.company_id=$company_id 
                where 1
            ";


        if ($client_status == "active") {
            $sql .= " and c.connection_mode = 1";
        } elseif ($client_status == "inactive") {
            $sql .= " and c.connection_mode = 0";
        } elseif ($client_status == "locked") {
            $sql .= " and c.connection_mode = 2";
        }

        if ($zone_id > 0) {
            $sql .= " and c.zone_id ='" . $zone_id . "'";
        }
        $sql .= "  group by q.client_id";
        $sql .= "  having payable > 0";
        $sql .= "  ORDER BY c.client_id ASC";

        $due_bills = DB::select($sql);

        $employees = Employees::company()->get();
        $zones = Zones::where("zone_type", 1)->company()->get();
        return view("back.reports.due.due_bill", compact("client_status", "due_bills", "zones", "employees", "zone_id"));
    }

    public function downloadPDF(Request $request)
    {
        //  set_time_limit(300);
        //$file_name =  "due_bill_".rand(0,9999).".pdf";

        $operation = $request->operation;
        $client_status = $request->client_status;
        $zone_id = $request->zone_id;
        $company_id = \Settings::company_id();
        $sql = "        
                SELECT sum(debit)-sum(credit) payable, q.client_id, c.address, c.note,c.termination_date,c.client_id username, c.client_name,c.cell_no, c.connection_mode, c.billing_responsible,p.package_name, p.package_price,z.zone_name_en FROM (

                    SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE company_id =$company_id  GROUP BY client_id

                    UNION ALL 

                    SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit, client_id FROM bill_receives WHERE company_id = $company_id  GROUP BY client_id

                ) AS q 
                INNER JOIN clients c ON c.auth_id=q.client_id AND c.company_id=$company_id
                LEFT JOIN packages p ON p.id=c.package_id AND p.company_id=$company_id 
                LEFT JOIN zones z ON z.id=c.zone_id AND z.company_id=$company_id 
                where 1
            ";


        if ($client_status == "active") {
            $sql .= " and c.connection_mode = 1";
        } elseif ($client_status == "inactive") {
            $sql .= " and c.connection_mode = 0";
        } elseif ($client_status == "locked") {
            $sql .= " and c.connection_mode = 2";
        }

        if ($zone_id > 0) {
            $sql .= " and c.zone_id ='" . $zone_id . "'";
        }
        $sql .= "  group by q.client_id";
        $sql .= "  having payable > 0";
        $sql .= "  ORDER BY c.client_id ASC";

        $due_bills = DB::select($sql);

        $data = [
            'due_bills' => $due_bills,
            'operation' => $operation
        ];

        if ($operation == "Print") {

            return view('back.reports.due.due_print', compact('data'));

        } else {
            $data = $due_bills;

            $pdf = PDF::loadView('back.reports.due.pdf', compact('data'), [], [
                'mode' => 'utf-8',
                'orientation' => 'P',
                // 'default_font_size'=> '20',
                'format' => 'A4',
                'title' => 'ISP Due Bill',
                //'margin_top' => 0
            ]);
            return $pdf->stream('due_bill.pdf');
        }
    }
}
