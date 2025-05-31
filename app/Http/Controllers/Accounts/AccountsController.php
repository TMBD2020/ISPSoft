<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Model\Bills;
use Illuminate\Http\Request;
use App\Services\BillService;
use App\Model\Employees;
use Illuminate\Foundation\Auth\User;
use DB;

class AccountsController extends Controller
{

    protected $billService;

    public function __construct(
        BillService $billService
    )
    {
        $this->billService = $billService;
    }
    public function index(){
        return view('back.accounts.index');
    }
    public function balance_page(){
        $employees = User::select("users.id as admin_id","users.email", "auth_id", "emp_name", "emp_id", "name")
            ->leftJoin('employees', "auth_id", "=", "users.id")
            ->whereIn("users.user_type",['emp','admin'])
            ->where(["users.company_id" => \Settings::company_id()])
            //->where([$this->employees.".company_id" => \Settings::company_id()])
            ->get();
        return view('back.accounts.balance',compact('employees'));
    }
    public function search_account_balance(Request $request){
        $accounts = Bills::query()
            ->select(DB::raw("sum(receive_amount) receive_amount,receive_by,bill_month,bill_year"))
            ->where('receive_amount',">",0);

        if($request->collected_by){
            $accounts=$accounts->where('receive_by',$request->collected_by);
        }

        $accounts=$accounts->groupBy('receive_by')->get();
$data=[];
        foreach ($accounts as $row) {
            $data[]=[
                "id"=>$row->admin->email,
                "name"=>$row->admin->name,
                "bill_month"=>date("F",strtotime(date("Y-".$row->bill_month."-d"))),
                "bill_year"=>$row->bill_year,
                "receive_amount"=>$row->receive_amount,
            ];
        }


        return response()->json($data);
    }
    public function dashboard(Request $request){

        $company_id = \Settings::company_id();

        $today              = date("Y-m-d");

        $present_year_month = date("Y-m");
        $present_month = explode("-",$present_year_month)[1];
        $present_year = explode("-",$present_year_month)[0];

        $previous_year_month = date("Y-m",strtotime("-1 month ".$today));
        $previous_month = explode("-",$previous_year_month)[1];
        $previous_year = explode("-",$previous_year_month)[0];

        $isp_approval_pending = $this->billService->getApprovalPending();

        return response()->json(
            [
                "isp_approval_pending"      =>  $isp_approval_pending
            ]
        );
    }
}
