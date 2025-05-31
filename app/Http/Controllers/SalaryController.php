<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\SalaryDistributions;

class SalaryController extends Controller
{
    protected $department   = "departments";
    protected $designations = "designations";
    protected $employees    = "employees";
    protected $emp_salarys  = "emp_salarys";
    protected $salary_distributions  = "salary_distributions";
    protected $emp_advanced_salary = "emp_advanced_salary";

    public function index(){
        $department_list  = DB::table($this->department)->where("company_id",\Settings::company_id())->where("is_active",1)->get();
        $designation_list  =  DB::table($this->designations)->where("company_id",\Settings::company_id())->where("is_active",1)->get();

        return view("back.employee.salary", compact("department_list","designation_list"));
    }
    public function employee_list(Request $request){
        $emp_list  = DB::table($this->employees)
            ->where("emp_department_id",$request->department_id)
            ->where("emp_designation_id",$request->designation_id)
            ->where("is_resign",0)
            ->where("company_id",\Settings::company_id())
            ->get();
        if(count($emp_list)>0)
            return json_encode($emp_list);
        else
            return 0;
    }



    public function searchIndividualSalary (Request $request){

        $salary  = SalaryDistributions::query()
            ->where("ref_emp_id",$request->emp_id)
            ->where("salary_year",$request->salary_year)
            ->where("salary_month",$request->salary_month);



            $emp_salarys  = DB::table($this->emp_salarys)
                ->where("emp_id",$request->emp_id)
                ->where("is_active",1)
                ->first();


        $result = array(
            "salary_info"   => $emp_salarys,
            "salary"        =>$salary->count()>0? $salary->first():$salary->count(),
        );

        return json_encode($result);
    }

    public function saveMonthlySalary (Request $request){

        $emp_id         = $request->salary_emp_id;
        $salary_month   = $request->salary_month;
        $salary_year    = $request->salary_year;

        $title      = $request->title;
        $percent    = $request->percent;
        $amount     = $request->amount;

//        $salary=array();
//        for($i=0;$i<count($title);$i++){
//            $salary []=  $title[$i] . "|" . $percent[$i] . "|" . $amount[$i]  ;
//        }
        $salary_amount= [];
        for($i=0;$i<count($title);$i++){
            $salary_amount []=  $amount[$i]  ;
        }


        $emp_late_fine      = $request->emp_late_fine;
        $emp_absent_fine    = $request->emp_absent_fine;
        $emp_others_fine    = $request->emp_others_fine;
        $advanced_salary    = $request->emp_advanced_salary;

        $data = [
            "company_id"        => \Settings::company_id(),
            "ref_emp_id"        => $emp_id,
            "salary_month"      => $salary_month,
            "salary_year"       => $salary_year,
            "emp_salary"        => array_sum($salary_amount),
            "emp_late_fine"     => $emp_late_fine,
            "emp_absent_fine"   => $emp_absent_fine,
            "emp_others_fine"   => $emp_others_fine,
            "advanced_salary"   => $advanced_salary,
            "advance_deduction" => 0,
            "created_by"        => Auth::user()->id,
            "approved_by"       => 0,
            "is_approved"       => 0,
            "created_at"        => date("Y-m-d H:i:s"),
            "updated_at"        => date("Y-m-d H:i:s"),
        ];

        if($request->action==1){
            if($advanced_salary>0){
                $salary_advanced = [
                    "company_id"        => \Settings::company_id(),
                    "ref_expense_id"    =>  $request->expense_id,
                    "payment_amount"    =>  $advanced_salary,
                    "ref_emp_id"        =>  $emp_id,
                    "is_installment"    =>  1,//installment deposit
                    "receive_date"      =>  date("Y-m-d"),
                    "advance_amount"    =>  0,//null
                    "installment_time"  =>  0,//null
                    "due_installment"   =>  0,
                    "created_at"        => date("Y-m-d H:i:s"),
                    "updated_at"        => date("Y-m-d H:i:s"),
                ];
                DB::table($this->emp_advanced_salary)->insert($salary_advanced);

                $installment_time = $request->installment_time;
                $total_installment = DB::table($this->emp_advanced_salary)
                    ->where("ref_expense_id",$request->expense_id)
                    ->where("is_installment",'!=',0)
                    ->count();
                //echo $installment_time;
                //echo "install:<br>";
                //echo $total_installment;
               // echo "total:<br>";
                if($total_installment==$installment_time){//if full installment paid due installment 0
                    $is =DB::table($this->emp_advanced_salary)
                        ->where("ref_expense_id",$request->expense_id)
                        ->update(["due_installment"=>0]);
                    if($is){
                      //  echo "update all:<br>";
                    }

                }
            }
            $result = SalaryDistributions::query()->insert($data);
        }else{
            $result = SalaryDistributions::query()
                ->whereId($request->salary_id)
                ->update($data);
        }


        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function salary_report(){
        $due_bills=$this->getSalaryReport([]);
        $month=date('m');
        $year=date('Y');
        return view("back.reports.salary.salary_report",compact("month","year","due_bills"));
    }

    public function salary_report_filter(Request $request){
        $due_bills=$this->getSalaryReport($request);
        $month=$request->month;
        $year=$request->year;
        return view("back.reports.salary.salary_report",compact("month","year","due_bills"));
    }


    public function getSalaryReport($request){
        if($request){
            $zone_id=$request->zone_id;
            return SalaryDistributions::query()
                //->select(DB::raw("(SUM(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as payable,bill_date,client_id"))
                ->where(["salary_month"=>$request->month,"salary_year"=>$request->year])

                //->groupBy("client_id")
                //->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)))"),">",0)
                ->get();
        }else{
            return SalaryDistributions::query()
               // ->select(DB::raw("(SUM(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as payable,bill_date,client_id"))
                ->where(["salary_month"=>date('m'),"salary_year"=>date("Y")])
                //->groupBy("client_id")
                //->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)))"),">",0)
                ->get();
        }

    }

}
