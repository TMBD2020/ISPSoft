<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;

class ExpenseReportController extends Controller
{
    protected $expenses = "expenses";
    protected $employees = "employees";
    protected $expense_heads = "expense_heads";
    public  function index(){
        $date_from = Carbon::now()->startOfMonth()->toDateString();
        $date_to = date("Y-m-d");
        $employees = DB::table($this->employees)->get();
        $expense_heads = DB::table($this->expense_heads)->get();
        return view("back.reports.expense.expense",compact("date_from","date_to","employees","expense_heads"));
    }

    public function search_expense_report(Request $request)
    {
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));
        $search_expense_head = $request->expense_head;
        $search_expense_by = $request->expense_by;


        if($search_expense_head!=0){
            $where = [
                'expense_head_id'   =>  $search_expense_head,
                "expense_status"    =>  1
            ];
        }
        if($search_expense_by!=0){
            $where = [
                'responsible_person'   =>  $search_expense_by,
                "expense_status"    =>  1
            ];
        }

        if($search_expense_by!=0 and $search_expense_head!=0){
            $where = [
                'responsible_person'    =>  $search_expense_by,
                'expense_head_id'       =>  $search_expense_head,
                "expense_status"        =>  1
            ];
        }
        if($search_expense_by==0 and $search_expense_head==0){
            $where = [
                "expense_status"        =>  1
            ];
        }


        $expense1 = DB::table($this->expenses)
            ->select(
                "expense_head_name","approver.emp_name as approve_by_name","expenser.emp_name as expense_by_name",
                "expense_amount","expense_note","expense_date"
            )
            ->leftJoin($this->expense_heads,$this->expense_heads.".id","=",$this->expenses.".expense_head_id")
            ->leftJoin($this->employees." as expenser","expenser.id","=",$this->expenses.".responsible_person")
            ->leftJoin($this->employees." as approver","approver.id","=",$this->expenses.".approved_by")
        ->whereBetween("expense_date",[$date_from,$date_to])
        ->where($where)
            ->get();
        $all_expenses = $expense_data = array();
        if(count($expense1)>0){
            foreach ($expense1 as $expense) {

                $expense_data[] = [
                    "expense_name"  =>$expense->expense_head_name,
                    "expense_by"    =>$expense->expense_by_name,
                    "approved_by"   =>$expense->approve_by_name,
                    "expense_amount"=>$expense->expense_amount,
                    "expense_note"  =>$expense->expense_note,
                    "expense_date"  =>date("d.m.Y", strtotime($expense->expense_date)),
                ];
            }

            $all_expenses = [
                "expense"=> $expense_data,
                "dates"=>[
                    "date_from"=>date("d.m.Y",strtotime($date_from)),
                    "date_to"=>date("d.m.Y",strtotime($date_to))
                ]
            ];
        }
        else{
            $all_expenses = [
                "expense"=>0,
                "dates"=>[
                    "date_from" =>date("d.m.Y",strtotime($date_from)),
                    "date_to"   =>date("d.m.Y",strtotime($date_to))
                ]
            ];
        }

        return json_encode($all_expenses);
    }

    public function downloadPDF(Request $request) {

        $date_from= explode(".",$request->date_from);
        $date_to= explode(".",$request->date_to);

        $date_from = $date_from[2]."-".$date_from[1]."-".$date_from[0];
        $date_to = $date_to[2]."-".$date_to[1]."-".$date_to[0];
        $search_expense_head = $request->expense_head;
        $search_expense_by = $request->expense_by;


        if($search_expense_head!=0){
            $where = [
                'expense_head_id'   =>  $search_expense_head,
                "expense_status"    =>  1
            ];
        }
        if($search_expense_by!=0){
            $where = [
                'responsible_person'   =>  $search_expense_by,
                "expense_status"    =>  1
            ];
        }

        if($search_expense_by!=0 and $search_expense_head!=0){
            $where = [
                'responsible_person'    =>  $search_expense_by,
                'expense_head_id'       =>  $search_expense_head,
                "expense_status"        =>  1
            ];
        }
        if($search_expense_by==0 and $search_expense_head==0){
            $where = [
                "expense_status"        =>  1
            ];
        }
        $expenses = DB::table($this->expenses)
            ->select(
                "expense_head_name","approver.emp_name as approve_by_name","expenser.emp_name as expense_by_name",
                "expense_amount","expense_note","expense_date"
            )
            ->leftJoin($this->expense_heads,$this->expense_heads.".id","=",$this->expenses.".expense_head_id")
            ->leftJoin($this->employees." as expenser","expenser.id","=",$this->expenses.".responsible_person")
            ->leftJoin($this->employees." as approver","approver.id","=",$this->expenses.".approved_by")
            ->whereBetween("expense_date",[$date_from,$date_to])
            ->where($where)
            ->get();

        $all_expenses = $expense_data = array();
        if(count($expenses)>0){
            foreach ($expenses as $expense) {
                $expense_data[] = [
                    "expense_name"  =>$expense->expense_head_name,
                    "expense_by"    =>$expense->expense_by_name,
                    "approved_by"   =>$expense->approve_by_name,
                    "expense_amount"=>$expense->expense_amount,
                    "expense_note"  =>$expense->expense_note,
                    "expense_date"  =>date("d.m.Y", strtotime($expense->expense_date)),
                ];
            }
            $all_expenses = [
                "expense"=> $expense_data,
                "dates"=>[
                    "date_from"=>date("d.m.Y",strtotime($date_from)),
                    "date_to"=>date("d.m.Y",strtotime($date_to))
                ]
            ];
        }
        else{
            $all_expenses = [
                "expense"=>0,
                "dates"=>[
                    "date_from" =>date("d.m.Y",strtotime($date_from)),
                    "date_to"   =>date("d.m.Y",strtotime($date_to))
                ]
            ];
        }

        $file_name =  "expense_".rand(0,9999).".pdf";
        $operation = $request->operation;

        if($operation=="Print"){
            return view('back.reports.expense.expense_pdf',compact('all_expenses','operation'));
        }else{
            $pdf = PDF::loadView('back.reports.expense.expense_pdf', compact('all_expenses','operation'));
            return $pdf->download($file_name);
        }
    }
}
