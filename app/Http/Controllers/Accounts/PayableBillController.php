<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use DB;
use Carbon\Carbon;
use App\Models\PayableBills;

class PayableBillController extends Controller
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
        return view("back.accounts.payable_bill", compact("expense_heads","employees","date_from","date_to"));
    }

    public function save_expense(Request $request)
    {
        if($request->action==1){
            $data=[
                "company_id"            =>auth()->user()->company_id,
                "expense_head_id"       => $request->expense_head_id,
                "responsible_person"    => $request->responsible_person,
                "expense_voucher_no"    => $request->expense_voucher_no,
                "expense_amount"        => $request->expense_amount,
                "expense_date"          => date("Y-m-d", strtotime(str_replace("/","-",$request->expense_date))),
                "expense_note"          => $request->expense_note,
                "created_at"            => date("Y-m-d H:i:s"),
                "updated_at"            => date("Y-m-d H:i:s")
            ];
            if(app('App\Http\Controllers\Access\CheckPermissionController')->module(10,"approve_access")==1){
                $data = array_merge(["expense_status"=>1],$data);
            }

            $result = DB::table($this->expenses)->insert($data);
        }else{
            $data =array(
                "company_id"            =>auth()->user()->company_id,
                "expense_head_id"       => $request->expense_head_id,
                "responsible_person"    => $request->responsible_person,
                "expense_voucher_no"    => $request->expense_voucher_no,
                "expense_amount"        => $request->expense_amount,
                "expense_date"          => date("Y-m-d", strtotime(str_replace("/","-",$request->expense_date))),
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

    public function expenseDataTable(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'expense_head_name',
            2 =>'expense_head_note'
        );

        $totalData =PayableBills::company()->count();

        $totalFiltered = $totalData;

        // $limit = $request->input('length');
        // $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = PayableBills::company()
            //    ->offset($start)
            //     ->limit($limit)
            //     ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   PayableBills::company()
                ->where('id','LIKE',"%{$search}%")
                ->orWhere('expense_head_name', 'LIKE',"%{$search}%")
                // ->offset($start)
                // ->limit($limit)
                // ->orderBy($order,$dir)
                ->get();

            $totalFiltered =   PayableBills::company()
                ->where('id','LIKE',"%{$search}%")
                ->orWhere('expense_head_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->payment_date;
                $nestedData[] = $post->voucher_no;
                $nestedData[] = $post->account->account_name;
                $nestedData[] = $post->emp->account_name;
                $nestedData[] = $post->amount;
                $nestedData[] = $post->note;
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

    public function expenseHeadList(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'expense_head_name',
            2 =>'expense_head_note'
        );

        $totalData = DB::table($this->expense_heads)
            ->where(["company_id"            =>auth()->user()->company_id])->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->expense_heads)
                ->where(["company_id" =>auth()->user()->company_id])->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->expense_heads)
                ->where(["company_id" =>auth()->user()->company_id])
                ->where('id','LIKE',"%{$search}%")
                ->orWhere('expense_head_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->expense_heads)
                ->where(["company_id" =>auth()->user()->company_id])
                ->where('id','LIKE',"%{$search}%")
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
                "company_id" =>auth()->user()->company_id,
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
        $result =  DB::table($this->expense_heads)
            ->where(["company_id" =>auth()->user()->company_id])->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function check_previous_advanced_salary(Request $request)
    {
        $result = DB::table($this->emp_advanced_salary)
            ->where(["company_id" =>auth()->user()->company_id])
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
            ->where(["company_id" =>auth()->user()->company_id])
            ->where("ref_emp_id",$request->emp_id)
            ->where("due_installment",1);
        if($check->count()>0){
            $data = $check->first();
            $expense_id = $data->ref_expense_id;
            $installment_time = $data->installment_time;

            $adv_salary = DB::table($this->emp_advanced_salary)
                //->select(DB::raw("advance_amount as adv_salary"))
                ->where(["company_id" =>auth()->user()->company_id])
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
                "company_id" =>auth()->user()->company_id,
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
                        "company_id" =>auth()->user()->company_id,
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
