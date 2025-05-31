<?php

namespace App\Http\Controllers;

use App\Models\Boxes;
use App\Models\CableTypes;
use App\Models\ClientTypes;
use App\Models\Employees;
use App\Models\IdPrefixs;
use App\Models\IdTypes;
use App\Models\IspResellerBills;
use App\Models\IspBillHistorys;
use App\Models\Microtiks;
use App\Models\Packages;
use App\Models\PaymentMethods;
use App\Models\Zones;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Auth;
use DB;
use Hash;
use DateTime;
use image;
use App\Models\TmbdUsers;
use App\Models\IspResellers;

class ResellerController extends Controller
{
    protected $connectivity_types = "connectivity_types";

    public function __construct()
    {
        $this->middleware("auth");
    }

    function dateConvert($date){
        $date = str_replace("/","-",$date);
        return date("Y-m-d",strtotime($date));
    }

    public function index()
    {
        $packages = Packages::query()->where(["package_type"=>"reseller","company_id"=>\Settings::company_id()])->get();
        $zones = Zones::query()->where(["zone_type"=>1,"company_id"=>\Settings::company_id()])->get();
        $networks = Microtiks::query()->where(["company_id"=>\Settings::company_id()])->get();
        $cable_types = CableTypes::all();
        $connectivity_types = DB::table($this->connectivity_types)->get();
        $payment_method = PaymentMethods::query()->where(["company_id"=>\Settings::company_id()])->get();
        $boxes = Boxes::query()->where(["company_id"=>\Settings::company_id()])->get();
        $client_types = ClientTypes::all();
        $technicians = Employees::query()->where(["company_id"=>\Settings::company_id()])->get();
        return
            view(
                "back.reseller.isp_reseller",
                compact(
                   "packages", "zones", "networks", "cable_types",
                    "connectivity_types", "payment_method", "boxes", "client_types",
                    "technicians"
                )
            );
    }

    public function save_reseller(Request $request)
    {
        if ($request->action == 1) {
            $data = array();
            $data["company_id"] = \Settings::company_id();
            $data["network_id"] = $request->network_id;
            $data["pop_id"] = $request->pop_id;
            $data["package_id"] = $request->package_id;
            $data["reseller_name"] = $request->reseller_name;
            $data["reseller_id"] = $request->reseller_username;
            $data["f_name"] = $request->f_name;
            $data["m_name"] = $request->m_name;
            $data["reseller_nid"] = $request->reseller_nid;
            $data["reseller_email"] = $request->reseller_email;
            if($request->reseller_dob) {
                $data["reseller_dob"] = $this->dateConvert($request->reseller_dob);
            }
            $data["reseller_join_date"] = $this->dateConvert($request->reseller_join_date);
            $data["reseller_sex"] = $request->reseller_sex;
            $data["reseller_blood"] = $request->reseller_blood;
            $data["personal_contact"] = $request->personal_contact;
            $data["office_contact"] = $request->office_contact;
            $data["reseller_present_add"] = $request->reseller_present_add;
            $data["reseller_permanent_add"] = $request->reseller_permanent_add;
            $data["permanent_discount_amount"] = $request->permanent_discount_amount;
            $data["reseller_skype"] = $request->reseller_skype;
            $data["note"] = $request->note;
            $data["reseller_activity"] = 1;
            $data["reseller_type"] = $request->reseller_type;
            $data["created_at"] = date("Y-m-d H:i:s");

            if($request->reseller_type=="Bandwidth"){
                $bandwidth=[];
                $bandwidthTotalPrice=[];
                foreach ($request->title as $key=>$row) {
                    $bandwidth[]=[
                        "title"=>$row,
                        "qty"=>$request->qty[$key],
                        "price"=>$request->price[$key],
                    ];

                    $bandwidthTotalPrice[]=$request->qty[$key]*$request->price[$key];
                }

                if($bandwidth){
                    $data["bandwidth_details"] = json_encode($bandwidth);
                }
            }else{
                $data["bandwidth_details"] = '';
            }

            $user = new User();
            $user->name = $data["reseller_name"];
            $user->user_id = $request->reseller_username;
            $user->company_id =\Settings::company_id();
            $user->email = $user->user_id;
            $user->password = Hash::make($request->reseller_password);
            $user->is_admin = 0;
            $user->user_type = "reseller";
            $user->ref_role_id = "0";
            $user->activation_date = $data["reseller_join_date"];
            $user->is_active = $data["reseller_activity"];
            $user->save();
            $id = $user->id;
            $data["auth_id"] = $id;

            if($user){
                $result = IspResellers::insert($data);
                if ($result) {
                    if ($request->hasFile("reseller_image")) {
                        $file = $request->file("reseller_image");
                        if($user){
                            $name = $user->user_id . "." . $file->guessClientExtension();
                            $data["reseller_image"] = 'app-assets/images/reseller/' . $name;
                            //Image::make($file)->resize(150, 150)->save($data["picture"]);
                            $file->move(public_path('app-assets/images/reseller/'), $name);
                        }
                    }




                    if ($request->previous_due > 0) {

                        //create bill

                        $this_year = date("Y");
                        $this_month = date("m");
                        $bill_type = 1;
                        $bill_count = 1;
                        $bill_id = $this_year . $this_month . $bill_type . $bill_count . $id;
                        $bill["company_id"] = \Settings::company_id();
                        $bill["client_id"] = $id;
                        $bill["client_initial_id"] = $data["reseller_id"];
                        $bill["bill_id"] = $bill_id;
                        $bill["bill_date"] = date("Y-m-d");
                        $bill["bill_month"] = $this_month;
                        $bill["bill_year"] = $this_year;
                        $bill["bill_type"] = $bill_type;
                        $bill["bill_status"] = 1;
                        $bill["previous_amount"] = $request->previous_due ? $request->previous_due :0;
                        $bill["payable_amount"] = $request->previous_due;//calculated with previous bill // discount // permanent discount
                        $bill["permanent_discount_amount"] = $request->permanent_discount_amount? $request->permanent_discount_amount :0;
                        $bill["created_at"]= date("Y-m-d H:i");
                        $bill["updated_at"]= date("Y-m-d H:i");


                        $history=array();
                        $history["particular"]= "Monthly bill";
                        $history["company_id"] = \Settings::company_id();
                        $history["client_id"]= $bill["client_id"];
                        $history["bill_id"]= $bill["bill_id"];
                        $history["bill_year"]= $bill["bill_year"];
                        $history["bill_month"]= $bill["bill_month"];
                        $history["bill_amount"]= $bill["payable_amount"];
                        $history["receive_amount"]= 0;
                        $history["bill_type"]= 'reseller';
                        $history["created_at"]= date("Y-m-d H:i");
                        $history["updated_at"]= date("Y-m-d H:i");
                        $historyData[]=$history;


                        $insert_bill = IspResellerBills::query()->insert($bill);
                        if($insert_bill){
                            if($historyData){
                                IspBillHistorys::query()->insert($historyData);
                            }
                        }
                        //end bill creation
                    }
                }
            }


        } else {
            $data = array();
            $data["network_id"] = $request->network_id;
            $data["pop_id"] = $request->pop_id;
            $data["package_id"] = $request->package_id;
            $data["reseller_name"] = $request->reseller_name;
            $data["reseller_id"] = $request->reseller_username;
            $data["f_name"] = $request->f_name;
            $data["m_name"] = $request->m_name;
            $data["reseller_nid"] = $request->reseller_nid;
            $data["reseller_email"] = $request->reseller_email;
           if($request->reseller_dob) {
                $data["reseller_dob"] = $this->dateConvert($request->reseller_dob);
            }
            $data["reseller_join_date"] = $this->dateConvert($request->reseller_join_date);
            $data["reseller_sex"] = $request->reseller_sex;
            $data["permanent_discount_amount"] = $request->permanent_discount_amount;
            $data["reseller_blood"] = $request->reseller_blood;
            $data["personal_contact"] = $request->personal_contact;
            $data["office_contact"] = $request->office_contact;
            $data["reseller_present_add"] = $request->reseller_present_add;
            $data["reseller_permanent_add"] = $request->reseller_permanent_add;
            $data["reseller_skype"] = $request->reseller_skype;
            $data["note"] = $request->note;
            $data["reseller_type"] = $request->reseller_type;
            $data["updated_at"] = date("Y-m-d H:i:s");

            if($request->reseller_type=="Bandwidth"){
                $bandwidth=[];
                foreach ($request->title as $key=>$row) {
                    $bandwidth[]=[
                        "title"=>$row,
                        "qty"=>$request->qty[$key],
                        "price"=>$request->price[$key],
                    ];
                }

                if($bandwidth){
                    $data["bandwidth_details"] = json_encode($bandwidth);
                }
            }else{
                $data["bandwidth_details"] = '';
            }
            if ($request->hasFile("reseller_image")) {
                $reseller_data = IspResellers::find($request->id);
                $client_id = $reseller_data->auth_id;
                $client_pic = $reseller_data->reseller_image;
                if($client_pic){
                    if (file_exists(public_path($client_pic))) {
                        unlink(public_path($client_pic));
                    }
                }

                $file = $request->file("reseller_image");
                $name = $client_id . "." . $file->guessClientExtension();
                $data["reseller_image"] = 'app-assets/images/reseller/' . $name;
                $file->move(public_path('app-assets/images/reseller/'), $name);
            }

            $result = IspResellers::whereId($request->id)->update($data);

            //update password
            if ($result) {
                if ($request->reseller_password) {
                    $auth = IspResellers::whereId($request->id)->first();
                    User::find($auth->auth_id)->update(['password' => Hash::make($request->reseller_password)]);
                }
            }
        }

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ResellerList(Request $request)
    {

        $columns = array(
            0 => 'id',
            1 => 'reseller_id',
            5 => 'network_id',
            6 => 'pop_id',
        );

        $totalData = IspResellers::where("company_id",\Settings::company_id())->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = IspResellers::query()
                ->where('company_id', \Settings::company_id())
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = IspResellers::query()
                ->where('id', '=', "{$search}")
                ->where('company_id', \Settings::company_id())
                ->orWhere('reseller_name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = IspResellers::where('id', '=', "{$search}")
                ->orWhere('reseller_name', 'LIKE', "%{$search}%")
                ->where('company_id', \Settings::company_id())
                ->count();
        }

        $data = array();
        if (!empty($posts)) {
            $i=$start;
            $j=$start+ ($limit>count($posts)?count($posts):$limit);
            foreach ($posts as $post) {
                $nestedData = array();
                if($dir=='desc'){
                    $i++;
                }else{
                    $i=$j--;
                }
                $nestedData[] = $i;
                $nestedData[] = $post->reseller_id;
                $nestedData[] = $post->reseller_name;
                $nestedData[] = $post->personal_contact;
                $nestedData[] = $post->reseller_present_add;
                $nestedData[] = $post->network->network_name;
                $nestedData[] = $post->pop->pop_name;
                $nestedData[] = '<div class="btn-group align-middle resellerId' . $post->id . '" role="group">
                <button id="' . $post->id . '" class="update edit' .$post->id . ' btn btn-primary btn-sm badge">
                <span class="ft-edit"></span> Edit</button>
                </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ];

        echo json_encode($json_data);

    }

    public function resellerUpdate(Request $request)
    {
        $result = IspResellers::find($request->id);
        if($result):
        return response()->json(['status'=>true,'data'=>$result]);
        else:
        return response()->json(['status'=>false]);
        endif;
    }



    public function last_reseller_id()
    {
        $id_type = IdTypes::query()
            ->where('id', 1)
            ->first()->id;

        if ($id_type) {
            $id_prefix = IdPrefixs::query()->where(['ref_id_type_name'=> $id_type,"company_id"=>\Settings::company_id()])->first();
            $initial_id_digit = $id_prefix->initial_id_digit;
            $prefix_name = $id_prefix->id_prefix_name;

            $total_client = IspResellers::where("company_id",\Settings::company_id())->count();

            $new_client_id = $initial_id_digit + $total_client + 1;
            $new_client_id = $prefix_name . $new_client_id;

            echo $new_client_id;
        } else {
            echo 0;
        }


    }
}
