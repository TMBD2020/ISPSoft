<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use DB;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    protected $expense_heads = "expense_heads";
    protected $expenses = "expenses";
    protected $employees = "employees";
    protected $emp_advanced_salary = "emp_advanced_salary";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        $date_from = date("d/m/Y", strtotime(Carbon::now()->startOfMonth()->toDateString()));
        $date_to = date("d/m/Y");
        $expense_heads = DB::table($this->expense_heads)->get();
        $employees = DB::table($this->employees)->get();
        return view("back.expense.expense", compact("expense_heads","employees","date_from","date_to"));
    }

    public function save_expense(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->expenses)->insert(
                [
                    "expense_head_id"       => $request->expense_head_id,
                    "responsible_person"    => $request->responsible_person,
                    "expense_voucher_no"    => $request->expense_voucher_no,
                    "expense_amount"        => $request->expense_amount,
                    "expense_date"          => date("Y-m-d", strtotime($request->expense_date)),
                    "expense_note"          => $request->expense_note,
                    "created_at"            => date("Y-m-d H:i:s"),
                    "updated_at"            => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "expense_head_id"       => $request->expense_head_id,
                "responsible_person"    => $request->responsible_person,
                "expense_voucher_no"    => $request->expense_voucher_no,
                "expense_amount"        => $request->expense_amount,
                "expense_date"          => date("Y-m-d", strtotime($request->expense_date)),
                "expense_note"          => $request->expense_note,
                "created_at"            => date("Y-m-d H:i:s"),
                "updated_at"            => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->expenses)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function expenseView(Request $request)
    {
        $date_from = date("Y-m-d", strtotime(str_replace("/","-",$request->date_from)));
        $date_to = date("Y-m-d", strtotime(str_replace("/","-",$request->date_to)));

        $posts = DB::table($this->expenses)
            ->select("*", $this->expenses.".id as expense_id")
            ->leftJoin($this->expense_heads,$this->expenses.".expense_head_id","=",$this->expense_heads.".id")
            ->leftJoin($this->employees,$this->expenses.".responsible_person","=",$this->employees.".id")
            ->whereBetween("expense_date",[$date_from,$date_to])
            ->orderBy("expense_status","asc")
            ->orderBy("expense_date","desc")
            ->get();

        $html='';
        if(!empty($posts))
        {
            $html.='<table class="table table-bordered">';
            $html.='<thead>';
            $html.='<tr>';
            $html.='<th class="text-center">SL</th>';
            $html.='<th class="text-center">Voucher No</th>';
            $html.='<th class="text-center">Date</th>';
            $html.='<th>Head</th>';
            $html.='<th>Employee</th>';
            $html.='<th class="text-right">Amount</th>';
            $html.='<th class="text-center">Action</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';

            foreach ($posts as $key=>$post)
            {
                $html.='<tr>';
                $html.='<td class="text-center">'.($key+1).'</td>';
                $html.='<td class="text-center">'.($post->expense_voucher_no).'</td>';
                $html.='<td class="text-center">'.($post->expense_date).'</td>';
                $html.='<td>'.($post->expense_head_name).'</td>';
                $html.='<td>'.($post->emp_name).'</td>';
                $html.='<td class="text-right">'.($post->expense_amount).'</td>';
                $html.='<td class="text-center">';
                    if($post->expense_status==0){
                        $html.='<div class="btn-group align-top" role="group">
                                <button id="' .($post->expense_id). '" class="update btn btn-primary btn-sm badge">Edit</button>
                                <button id="' .($post->expense_id). '" class="approve btn btn-danger btn-sm badge">Approve</button>
                            </div>';
                    }

            $html.='</td>';
                $html.='</tr>';
            }
            $html.='</tbody>';

            $html.='</table>';
        }
        echo $html;
    }

    public function expenseHeadList(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'expense_head_name',
            2 =>'expense_head_note'
        );

        $totalData = DB::table($this->expense_heads)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->expense_heads)->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->expense_heads)->where('id','LIKE',"%{$search}%")
                ->orWhere('expense_head_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->expense_heads)->where('id','LIKE',"%{$search}%")
                ->orWhere('expense_head_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->expense_head_name;
                $nestedData[] = $post->expense_head_note;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    public function expenseUpdate(Request $request)
    {
        $result =  DB::table($this->expenses)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function expenseApprove(Request $request)
    {
        $result = DB::table($this->expenses)->where("id",$request->id)->update([
            "expense_status"=>1,
            "approved_by"=>Auth::user()->id
        ]);
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function save_expense_head(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->expense_heads)->insert(
                [
                    "expense_head_code"       => $request->expense_head_code,
                    "expense_head_name"    => $request->expense_head_name,
                    "expense_head_note"    => $request->expense_head_note,
                    "created_at"            => date("Y-m-d H:i:s"),
                    "updated_at"            => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "expense_head_code"       => $request->expense_head_code,
                "expense_head_name"    => $request->expense_head_name,
                "expense_head_note"    => $request->expense_head_note,
                "created_at"            => date("Y-m-d H:i:s"),
                "updated_at"            => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->expense_heads)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function expenseHeadUpdate(Request $request)
    {
        $result =  DB::table($this->expense_heads)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function expenseHeadDelete(Request $request)
    {
        $result = DB::table($this->expense_heads)->where("id",$request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function expense_head_list(Request $request)
    {
        $result =  DB::table($this->expense_heads)->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function check_previous_advanced_salary(Request $request)
    {
        $result = DB::table($this->emp_advanced_salary)
            ->where("ref_emp_id",$request->emp_id)
            ->where("due_installment",1)
            ->count();
        if($result>0){
            echo $result;
        }else{
            echo 0;
        }
    }
    public function get_adv_salary(Request $request)
    {
        $check = DB::table($this->emp_advanced_salary)
            ->where("ref_emp_id",$request->emp_id)
            ->where("due_installment",1);
        if($check->count()>0){
            $data = $check->first();
            $expense_id = $data->ref_expense_id;
            $installment_time = $data->installment_time;

            $adv_salary = DB::table($this->emp_advanced_salary)
                //->select(DB::raw("advance_amount as adv_salary"))
                ->where("ref_expense_id",$expense_id)
                ->where("is_installment",0)
                ->get();

            if($adv_salary){
                //$value = $adv_salary[0]->adv_salary/$installment_time;
                echo json_encode($adv_salary);
            }
        }else{
            echo 0;
        }

    }


    public function save_advanced_salary(Request $request)
    {
        if($request->action==1){

            $expense = DB::table($this->expenses)->insert(
                [
                    "expense_head_id"       => $request->expense_head_id,
                    "responsible_person"    => $request->receive_from,
                    "expense_voucher_no"    => $request->salary_voucher_no,
                    "expense_amount"        => $request->advance_amount,
                    "expense_date"          => date("Y-m-d", strtotime($request->receive_date)),
                    "expense_note"          => "advanced salary",
                    "created_at"            => date("Y-m-d H:i:s"),
                    "updated_at"            => date("Y-m-d H:i:s")
                ]
            );
            if($expense){
                $expense_id = DB::getPdo()->lastInsertId();
                $result = DB::table($this->emp_advanced_salary)->insert(
                    [
                        "ref_expense_id"    => $expense_id,
                        "ref_emp_id"        => $request->ref_emp_id,
                        "advance_amount"    => $request->advance_amount,
                        "installment_time"  => $request->installment_time,
                        "receive_date"      => date("Y-m-d", strtotime($request->receive_date)),
                        "receive_from"      => $request->receive_from,
                        "is_installment"    => 0,
                        "payment_amount"    => 0,
                        "created_at"        => date("Y-m-d H:i:s"),
                        "updated_at"        => date("Y-m-d H:i:s")
                    ]
                );
            }else{
                $result=false;
            }

        }else{
            $data = [
                "advance_amount"    => $request->advance_amount,
                "installment_time"  => $request->installment_time,
                "receive_date"      => date("Y-m-d", strtotime($request->receive_date)),
                "receive_from"      => $request->receive_from,
                "updated_at"        => date("Y-m-d H:i:s")
            ];
            $result = DB::table($this->emp_advanced_salary)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }


}
