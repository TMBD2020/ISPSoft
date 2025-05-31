<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

class IncomeController extends Controller
{
    protected  $expenses = "expenses";
    protected  $expense_heads = "expense_heads";
    protected  $liabilities = "liabilities";
    protected  $bills = "bills";
    protected  $company_funds = "company_funds";
    protected  $package_changes = "package_changes";
    protected  $line_shifts = "line_shifts";
    protected  $clients = "clients";
    protected  $employees = "employees";

    public function index(){
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $data=[];
        return view("back.reports.income",compact("date_from","date_to","data"));
    }

    public function search_income_statement(Request $request)
    {
        //calculate opening balance
        $company_id = \Settings::company_id();
        $date_from = $request->date_from;
        $date_to   = $request->date_to;
        $opening_balance   = 0;
        $data=true;
        $sql="
            select SUM(credit)-SUM(debit) bal from (
                SELECT 0 debit, receive_amount credit, 1 AS ttype from bill_receives where receive_date < '".$request->date_from."' and  company_id= $company_id

                union all
                
                SELECT 0 debit, package_charge credit, 1 AS ttype from package_changes where change_date < '".$request->date_from."' and  company_id= $company_id 

                union all

                SELECT 0 debit, 0 credit, 1 AS ttype

            ) as q group by ttype
        ";
       // echo (nl2br($sql));
        $opening_balance =DB::select($sql);
        if( $opening_balance){          
            $opening_balance =  $opening_balance[0]->bal;
        }

        $sql="
            select sum(amount) amount, title from (
                select receive_amount amount, 'Bill Collection' title from bill_receives where receive_date between '".$request->date_from."' and '".$request->date_to."'  and  company_id= $company_id

                union all

                select 0 amount, 'Loan Received' title 

                union all

                select sum(amount) amount, 'Others' title from (
                    select sum(package_charge) amount, ref_client_id from package_changes where change_date between '".$request->date_from."' and '".$request->date_to."'  and  company_id= $company_id
                ) as t group by ref_client_id

            ) as q group by title
        ";

        $revenue_list =DB::select($sql);

        $sql="
            select sum(amount) amount from (
               select 0 amount, 'Loan Payment' title 
            ) as q group by title
        ";

        $expenses_list =DB::select($sql);



        // $opening_loan = DB::table($this->liabilities)
        //     ->select(DB::raw("sum(receive_amount) as loan_receive,sum(payment_amount) as loan_payment"))
        //     ->whereBetween("receive_date",[$previous_date_from,$previous_date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $line_shifts = DB::table($this->line_shifts)
        //     ->select(DB::raw("sum(shift_charge) as shift_charge"))
        //     ->whereBetween("shift_date",[$previous_date_from,$previous_date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $signup_fees = DB::table($this->clients)
        //     ->select(DB::raw("sum(signup_fee) as signup_fee"))
        //     ->where("connection_mode",1)
        //     ->whereBetween(DB::raw("date(created_at)"),[$previous_date_from,$previous_date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $package_charge = DB::table($this->package_changes)
        //     ->select(DB::raw("sum(package_charge) as package_charge"))
        //     ->whereBetween("change_date",[$previous_date_from,$previous_date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $opening_bills = DB::table($this->bills)
        //     ->select(DB::raw("sum(receive_amount) as bill_amount"))
        //     ->whereBetween("receive_date",[$previous_date_from,$previous_date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $opening_expenses = DB::table($this->expenses)
        //     ->select(DB::raw("sum(expense_amount) as expense_amount"))
        //     ->whereBetween("expense_date",[$previous_date_from,$previous_date_to])
        //     ->where("expense_status",1)
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $other_income   = $package_charge->package_charge+$line_shifts->shift_charge+$signup_fees->signup_fee;
        // $total_income   = $opening_loan->loan_receive+$opening_bills->bill_amount+$other_income;
        // $total_expense  = $opening_expenses->expense_amount+$opening_loan->loan_payment;

        // $opening_balance = number_format($total_income-$total_expense, 2, '.', '');

        // //new
        // $date_from = date("Y-m-d", strtotime($request->date_from));
        // $date_to = date("Y-m-d", strtotime($request->date_to));

        // $loan = DB::table($this->liabilities)
        //     ->select(DB::raw("sum(receive_amount) as loan_receive,sum(payment_amount) as loan_payment"))
        //     ->whereBetween("receive_date",[$date_from,$date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $signup_fees = DB::table($this->clients)
        //     ->select(DB::raw("sum(signup_fee) as signup_fee"))
        //     ->where("connection_mode",1)
        //     ->whereBetween(DB::raw("date(created_at)"),[$date_from,$date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $line_shifts = DB::table($this->line_shifts)
        //     ->select(DB::raw("sum(shift_charge) as shift_charge"))
        //     ->whereBetween("shift_date",[$date_from,$date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $package_charge = DB::table($this->package_changes)
        //     ->select(DB::raw("sum(package_charge) as package_charge"))
        //     ->whereBetween("change_date",[$date_from,$date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $bills = DB::table($this->bills)
        //     ->select(DB::raw("sum(receive_amount) as bill_amount"))
        //     ->whereBetween("receive_date",[$date_from,$date_to])
        //     ->where("company_id",\Settings::company_id())
        //     ->first();

        // $expenses = DB::table($this->expenses)
        //     ->select(DB::raw("sum(expense_amount) as expense_amount,expense_head_id,count(*) as head_count"))
        //     ->whereBetween("expense_date",[$date_from,$date_to])
        //     ->where("expense_status",1)
        //     ->where("company_id",\Settings::company_id())
        //     ->groupBy("expense_head_id")
        //     ->get();
        // $new_other_income = $package_charge->package_charge+$line_shifts->shift_charge+$signup_fees->signup_fee;
        // $expense_heads = DB::table($this->expense_heads)->get();

        // $all_expenses = array();
        // if(count($expenses)>0){
        //     foreach ($expenses as $expense) {
        //         foreach ($expense_heads as $head) {
        //             if($expense->expense_head_id==$head->id){
        //                 $all_expenses[] = [
        //                     "head_id"       =>$head->id,
        //                     "expense_name"  =>$head->expense_head_name,
        //                     "expense_amount"=>$expense->expense_amount,
        //                     "head_count"=>$expense->head_count
        //                 ];
        //             }
        //         }
        //     }
        // }else{
        //     foreach ($expense_heads as $head) {
        //         $all_expenses[] = [
        //             "head_id"       =>$head->id,
        //             "expense_name"  =>$head->expense_head_name,
        //             "expense_amount"=>0,
        //             "head_count"=>0
        //         ];
        //     }
        // }

       
        return view("back.reports.income",compact("date_from","date_to","revenue_list","expenses_list","opening_balance","data"));
    }

  

    public function expense_details(Request $request){
        $id = $request->id;
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));

        $expenses = DB::table($this->expenses)
            ->select(
                "approve_auth.emp_name as approved_by",
                "expense_auth.emp_name as expensed_by",
                "expense_amount",
                "expense_note as note"
            )
            ->leftJoin($this->employees." as expense_auth",$this->expenses.".responsible_person","=","expense_auth.id")
            ->leftJoin($this->employees." as approve_auth",$this->expenses.".approved_by","=","approve_auth.id")
            ->whereBetween("expense_date",[$date_from,$date_to])
            ->where(["expense_head_id"=>$id,"expense_status"=>1])
            ->get();

        return json_encode($expenses);
    }

}
