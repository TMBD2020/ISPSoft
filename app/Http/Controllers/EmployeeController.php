<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\Departments;
use App\Models\User;
use App\Models\Designations;
use App\Models\EmpSalarys;
use App\Models\SalaryDistributionSetting;
use App\Models\IdTypes;
use App\Models\IdPrefixs;
use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;


class EmployeeController extends Controller
{

    public function index()
    {
        //dd(\Settings::company_id());
        $emp_list = Employees::where("company_id", \Settings::company_id())->get();
        $department_list = Departments::where("company_id", \Settings::company_id())->get();
        $designation_list = Designations::where("company_id", \Settings::company_id())->get();
        $salary_settings = SalaryDistributionSetting::company()->orderBy("id", "asc")->get();
        return view("back.employee.employee", compact("department_list", "designation_list", "salary_settings", "emp_list"));
    }

    public function save_employee(Request $request)
    {
        if ($request->action == 1) {
            $emp = array();
            $emp["company_id"] = \Settings::company_id();
            $emp["emp_id"] = $request->emp_username;
            $emp["emp_name"] = $request->emp_name;
            $emp["emp_father"] = $request->emp_father;
            $emp["emp_mother"] = $request->emp_mother;
            $emp["emp_mobile"] = $request->emp_mobile;
            $emp["emp_email"] = $request->emp_email;
            $emp["emp_present_address"] = $request->emp_present_address;
            $emp["emp_permanent_address"] = $request->emp_permanent_address;
            $emp["emp_department_id"] = $request->emp_department_id;
            $emp["emp_designation_id"] = $request->emp_designation_id;
            $emp["emp_join_date"] = $request->emp_join_date;
            $emp["is_resign"] = $request->is_resign;
            if ($request->is_resign == 1) {
                $emp["emp_resign_date"] = $request->emp_resign_date;
            }

            //emergency contact
            $emp["relative_name"] = $request->relative_name;
            $emp["relative_mobile"] = $request->relative_mobile;
            $emp["relative_nid"] = $request->relative_nid;
            $emp["relative_relation"] = $request->relative_relation;
            $emp["relative_present_add"] = $request->relative_present_add;
            $emp["relative_permanent_add"] = $request->relative_permanent_add;

            if ($request->hasFile("emp_photo")) {
                $file = $request->file("emp_photo");
                $name = $emp["emp_id"] . "." . $file->guessClientExtension();
                $data["emp_photo"] = 'employee/photo/' . $name;
                $request->emp_photo->move(public_path('employee/photo/'), $name);
            }

            //create auth/login account
            $user = new User();
            $user->name = $emp["emp_name"];
            $user->user_id = $emp["emp_id"];
            $user->email = $emp["emp_id"];
            $user->password = Hash::make($request->emp_password);
            $user->is_admin = 0;
            $user->user_type = "emp";
            $user->ref_role_id = "0";
            $user->activation_date = date("Y-m-d");
            $user->is_active = 1;
            $user->company_id = \Settings::company_id();
            $user->save();
            $id = $user->id;
            $emp["auth_id"] = $id;




            $educational_qualification = $employed_history = array();
            if (count($request->exam) > 0) {
                $certificate = "certificate";
                if ($request->hasFile("certificate_image")) {
                    $files = $request->file("certificate_image");
                    foreach ($files as $key => $file) {
                        $certificate = $request->emp_username . "_" . strtolower(str_replace(" ", "_", $request->exam[$key])) . "." . $file->guessClientExtension();
                        $file->move('employee/certificate', $certificate);
                    }
                }
                for ($i = 0; $i < count($request->exam); $i++) {
                    $exam = $request->exam[$i] ? $request->exam[$i] : "-";
                    $college = $request->college[$i] ? $request->college[$i] : "-";
                    $passyear = $request->passyear[$i] ? $request->passyear[$i] : "-";
                    $result = $request->result[$i] ? $request->result[$i] : "-";

                    $educational_qualification[] = $exam . "+" . $college . "+" . $passyear . "+" . $result . "+" . $certificate;
                }
            }
            if (!empty($educational_qualification)) {
                $emp['educational_qualification'] = implode("^", $educational_qualification);
            }
            if (count($request->company) > 0) {
                for ($i = 0; $i < count($request->company); $i++) {
                    $company = $request->company[$i] ? $request->company[$i] : "-";
                    $designation = $request->designation[$i] ? $request->designation[$i] : "-";
                    $joined = $request->joined[$i] ? $request->joined[$i] : "-";
                    $resigned = $request->resigned[$i] ? $request->resigned[$i] : "-";

                    $employed_history[] = $company . "+" . $designation . "+" . $joined . "+" . $resigned;
                }
            }
            if (!empty($employed_history)) {
                $emp['employed_history'] = implode("^", $employed_history);
            }


            $result = Employees::create($emp);
            $emp_id = $result->id;

            $salaryData = array();
            $total = 0;
            if(is_array($request->salary_id)){
                for ($i = 0; $i < count($request->salary_id); $i++) {
                    $total += $request->salary_percent[$i];
                    $salaryData[] = $request->salary_id[$i] . "|" . $request->salary_title[$i] . "|" . $request->salary_percent[$i] . "|" . $request->{"row" . $request->salary_id[$i]};
                }
            }
           
            $available = 100 - $total;
            if ($available > 0) {
                $salaryData[] = "0|Other|" . $available . "|" . $request->other;
            }

            $salaryData = implode(",", $salaryData);
            EmpSalarys::create(
                [
                    "gross" => $request->gross,
                    "salary" => $salaryData,
                    "emp_id" => $emp_id
                ]
            );

        } else {
            $result = $this->update_employee_data($request);
        }
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function update_employee_data($request)
    {
        $emps = Employees::find($request->emp_id);

        $emps->emp_name = $request->emp_name;
        $emps->emp_father = $request->emp_father;
        $emps->emp_mother = $request->emp_mother;
        $emps->emp_mobile = $request->emp_mobile;
        $emps->emp_email = $request->emp_email;
        $emps->emp_present_address = $request->emp_present_address;
        $emps->emp_permanent_address = $request->emp_permanent_address;
        $emps->emp_department_id = $request->emp_department_id;
        $emps->emp_designation_id = $request->emp_designation_id;
        $emps->emp_join_date = $request->emp_join_date;
        $emps->is_resign = $request->is_resign;

        $educational_qualification = $employed_history = array();
        if (count($request->exam) > 0) {
            $certificate = "certificate";
            if ($request->hasFile("certificate_image")) {
                $files = $request->file("certificate_image");
                foreach ($files as $key => $file) {
                    $certificate = $request->emp_username . "_" . strtolower(str_replace(" ", "_", $request->exam[$key])) . "." . $file->guessClientExtension();
                    $file->move('employee/certificate', $certificate);
                }
            }
            for ($i = 0; $i < count($request->exam); $i++) {
                $exam = $request->exam[$i] ? $request->exam[$i] : "-";
                $college = $request->college[$i] ? $request->college[$i] : "-";
                $passyear = $request->passyear[$i] ? $request->passyear[$i] : "-";
                $result = $request->result[$i] ? $request->result[$i] : "-";

                $educational_qualification[] = $exam . "+" . $college . "+" . $passyear . "+" . $result . "+" . $certificate;
            }
        }
        if (!empty($educational_qualification)) {
            $emps->educational_qualification = implode("^", $educational_qualification);
        }
        if (count($request->company) > 0) {
            for ($i = 0; $i < count($request->company); $i++) {
                $company = $request->company[$i] ? $request->company[$i] : "-";
                $designation = $request->designation[$i] ? $request->designation[$i] : "-";
                $joined = $request->joined[$i] ? $request->joined[$i] : "-";
                $resigned = $request->resigned[$i] ? $request->resigned[$i] : "-";

                $employed_history[] = $company . "+" . $designation . "+" . $joined . "+" . $resigned;
            }
        }
        if (!empty($employed_history)) {
            $emps->employed_history = implode("^", $employed_history);
        }

        //emergency contact
        $emps->relative_name = $request->relative_name;
        $emps->relative_mobile = $request->relative_mobile;
        $emps->relative_nid = $request->relative_nid;
        $emps->relative_relation = $request->relative_relation;
        $emps->relative_present_add = $request->relative_present_add;
        $emps->relative_permanent_add = $request->relative_permanent_add;

        if ($request->is_resign == 1) {
            $emps->emp_resign_date = $request->emp_resign_date;
        }
        if ($request->hasFile("emp_photo")) {
            $file = $request->file("emp_photo");
            $name = $request->emp_username . "_" . time() . "." . $file->guessClientExtension();
            $emps->emp_photo = 'employee/photo/' . $name;
            $request->emp_photo->move(public_path('employee/photo/'), $name);
        }
        $result = $emps->save();
        //$result = DB::table($this->employees)->where('id', $request->emp_id)->update($data);

        //update password
        if ($result) {
            $userData = array();
            $userData["name"] = $emps->emp_name;
            if ($request->emp_password) {
                $userData["password"] = Hash::make($request->emp_password);
            }

            if ($request->hasFile("emp_photo")) {
                $userData["photo"] = $emps->emp_photo;
            }
            User::whereId($emps->auth_id)->update($userData);
        }
        if ($result) {
            if ($request->ck_salary_distribution == 1) {//has salary distribution
                $salaryData = array();
                $total = 0;
                for ($i = 0; $i < count($request->salary_id); $i++) {
                    $total += $request->salary_percent[$i];
                    $salaryData[] = $request->salary_id[$i] . "|" . $request->salary_title[$i] . "|" . $request->salary_percent[$i] . "|" . $request->{"row" . $request->salary_id[$i]};
                }
                $available = 100 - $total;
                if ($available > 0) {
                    $salaryData[] = "0|Other|" . $available . "|" . $request->other;
                }
                $salaryData = implode(",", $salaryData);
            } else {
                $salaryData = 0;
            }
            //salary information
            if(EmpSalarys::where('emp_id', $request->emp_id)->get()){
                EmpSalarys::query()
                ->where('emp_id', $request->emp_id)
                ->update(
                    array(
                        "gross" => $request->gross,
                        "salary" => $salaryData
                    )
                );
            }else{
                EmpSalarys::create(
                    [
                        "gross" => $request->gross,
                        "salary" => $salaryData,
                        "emp_id" => $request->emp_id
                    ]
                );
            }   
            
           
        }

        return $result;
    }

    public function employeeList(Request $request)
    {

        $columns = array(
            0 => "id",
            1 => "emp_id",
            2 => 'emp_name',
            3 => 'emp_department_id',
            4 => 'emp_designation_id',
        );

        $totalData = Employees::where("company_id", \Settings::company_id())->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        $filter = array();
        if (isset($request->_resign)) {
            $filter["is_resign"] = $request->_resign;
        }
        if (isset($request->_department)) {
            $filter["emp_department_id"] = $request->_department;
        }
        if (isset($request->_designation)) {
            $filter["emp_designation_id"] = $request->_designation;
        }
        $filter["company_id"] = \Settings::company_id();

        if (empty($request->input('search.value'))) {
            $posts = Employees::query()
                ->where($filter)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = trim($request->input('search.value'));

            $posts = Employees::query()
                ->where($filter)
                ->where('emp_id', '=', "$search")
                ->orWhere('emp_name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Employees::query()
                ->where($filter)
                ->where('emp_id', '=', "$search")
                ->orWhere('emp_name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->emp_id . "/<br>" . $post->emp_name;
                $nestedData[] = $post->department->department_name;
                $nestedData[] = $post->designation->designation_name;
                $nestedData[] = $post->emp_mobile;
                $nestedData[] = $post->emp_join_date;
                $nestedData[] = $post->is_active;
                $nestedData[] = '<div class="btn-group align-top" role="group">
                <button id="' . $post->id . '" class="update btn btn-primary btn-sm badge"> <span class="ft-edit"></span></button> 
            </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);

    }

    public function employeeUpdate(Request $request)
    {
        $emp_data = Employees::find($request->id);
        $salary_data = EmpSalarys::where(["emp_id" => $request->id])->first();

        $result = array(
            "emp_data" => $emp_data,
            "emp_salary" => $salary_data
        );

        return response()->json($result);

    }

    public function employeeDelete(Request $request)
    {
        $emp = Employees::find($request->id);
        User::find($emp->auth_id)->delete();
        return response()->json($emp->delete());
    }

    public function next_emp_id()
    {
        $id_type = IdTypes::find(3)->id;

        if ($id_type) {
            $id_prefix = IdPrefixs::where(['ref_id_type_name' => $id_type, "company_id" => \Settings::company_id()])->first();
            $initial_id_digit = $id_prefix->initial_id_digit;
            $prefix_name = $id_prefix->id_prefix_name;

            $total_emp = Employees::where("company_id", \Settings::company_id())->count();

            $new_emp_id = $initial_id_digit + $total_emp + 1;
            $new_emp_id = $prefix_name . $new_emp_id;

            return response()->json($new_emp_id);
        } else {
            echo 0;
        }
    }
}
