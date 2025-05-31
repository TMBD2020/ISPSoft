<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Employees;
use App\Models\Departments;

class EmpStorageController extends Controller
{

    protected $department = "departments";
    protected $designations =  "designations";
    protected $employees =  "employees";
    protected $emp_storages =  "emp_storages";

    public function index(){
        $departments = Departments::where("company_id", \Settings::company_id())->get();
        $emp_list  = Employees::where("company_id", \Settings::company_id())->where("is_resign",0)->get();
        return view("back.employee.liability", compact("departments","emp_list"));
    }


    public function save_store(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->emp_storages)->insert(
                [
                    "company_id"        => \Settings::company_id(),
                    "emp_id"            => $request->emp_id,
                    "product_name"      => $request->product_name,
                    "product_qty"       => $request->product_qty,
                    "ref_department_id" => $request->ref_department_id,
                    "receive_from"      => $request->receive_from,
                    "created_by"        => Auth::user()->id,
                    "receive_date"      => date("Y-m-d", strtotime($request->receive_date)),
                    "created_at"        => date("Y-m-d H:i:s"),
                    "updated_at"        => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "emp_id"            => $request->emp_id,
                "product_name"      => $request->product_name,
                "product_qty"       => $request->product_qty,
                "ref_department_id" => $request->ref_department_id,
                "receive_from"      => $request->receive_from,
                "receive_date"      => date("Y-m-d", strtotime($request->receive_date)),
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->emp_storages)->whereId($request->store_id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }


    public function storeList(Request $request)
    {


            if($request->id=='all'){
                $employee = DB::table($this->employees)
                    ->select("emp_name","id")
                    ->where("company_id", \Settings::company_id())
                    ->orderBy("id","asc")
                    ->get();
                $liability = DB::table($this->emp_storages)
                    ->select(DB::raw("emp_id, sum(product_qty) as product_qty"))
                    ->where("company_id", \Settings::company_id())
                    ->groupBy("emp_id")
                    ->orderBy("emp_id","asc")
                    ->get();
            }else{
                $employee = DB::table($this->employees)
                    ->select("emp_name","id")
                    ->where("company_id", \Settings::company_id())
                    ->where($this->employees.".id",$request->id)
                    ->get();
                $liability = DB::table($this->emp_storages)
                    ->select(DB::raw("emp_id, sum(product_qty) as product_qty"))
                    ->where($this->emp_storages.".emp_id",$request->id)
                    ->where("company_id", \Settings::company_id())
                    ->groupBy("emp_id")
                    ->orderBy("emp_id","asc")
                    ->get();
            }
            $result = [
                "employee"  => $employee,
                "liability" => $liability
            ];

        if(count($result)>0){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function liabilityView(Request $request)
    {
        $result = DB::table($this->emp_storages)
            ->select(DB::raw("*"))
            ->where("emp_id",$request->id)
            ->where("company_id", \Settings::company_id())
            ->get();

        if(count($result)>0){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }


    public function empLiabilityUpdate(Request $request)
    {
        $result = DB::table($this->emp_storages)
            ->select("*",$this->employees.".id as emp_pk_id",$this->emp_storages.".id as store_id")
            ->leftJoin($this->employees,$this->employees.".id","=",$this->emp_storages.".emp_id")
            ->leftJoin($this->department,$this->department.".id","=",$this->employees.".emp_department_id")
            ->where($this->emp_storages.".id",$request->id)
            ->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function delete(Request $request)
    {
        $result = DB::table($this->emp_storages)
            ->whereId($request->id)
            ->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

}
