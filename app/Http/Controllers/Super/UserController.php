<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TmbdUsers;
use Illuminate\Foundation\Auth\User;
use Validator;
use Hash;
use Illuminate\Validation\ValidationException;
use DB;
class UserController extends Controller
{

    public function index()
    {

        return view("super.users.index");
    }

    public function userList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'is_active'
        );

        $totalData = User::query()
            ->where("user_type", "super")
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = User::query()
                ->where("user_type", "super")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = User::query()

                ->where("user_type", "super")
                ->where('email', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = User::query()
                ->where("user_type", "super")
                ->where('email', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();

        if (!empty($posts)) {
            $i = count($posts) + 1;

            foreach ($posts as $key => $post) {
                if ($dir == 'desc') {
                    $i = $key + 1;
                } else {
                    $i--;
                }
                $nestedData = array();

                $nestedData[] = $i;
                $nestedData[] = $post->name;
                $nestedData[] = $post->email;
                if ($post->is_active == 1) {
                    $status = "<img src='" . asset("app-assets/images/active_icon.png") . "' style='width: 20px; height: 20px;' title='Active'> ";
                } else {
                    $status = "<img src='" . asset("app-assets/images/deactive_icon.png") . "' style='width: 20px; height: 20px;' title='Deactive'> ";
                }
                $nestedData[] = $status;
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                    <button id="' . $post->id . '" class="update btn btn-primary btn-sm badge">
                                    <span class="ft-settings"></span></button>
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

    function saveUser(Request $request)
    {
        if ($request->action == 1) {
            $validate = Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6'],
            ]);
            if (!$validate->fails()) {
                $users = new User();
                $users->name = $request->name;
                $users->email = $request->email;
                $users->password = Hash::make($request->password);
                $users->is_active = $request->status;
                $users->user_type = 'super';
                $users->company_id = 0;
                $users->save();
            } else {
                $users = $validate->messages()->get('*');
            }
        } else {

            $validate = Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ["unique:users,email,$request->id,id"]
            ]);
            if (!$validate->fails()) {
                $users = User::find($request->id);
                $users->name = $request->name;
                $users->email = $request->email;
                if ($request->password) {
                    $users->password = Hash::make($request->password);
                }

                $users->is_active = $request->status;
                $users->save();
            } else {
                $users = $validate->messages()->get('*');
            }
        }
        return response()->json($users);
    }

    public function edit(Request $request)
    {
        $user = User::find($request->id);
        return response()->json($user);
    }

    public function company_profile(Request $request)
    {
        $user = TmbdUsers::find($request->id);
        $tab = $request->tab;
        if ($tab == "profile") {
            return response()->json(view('super.company.profile', compact('user'))->render());
        } elseif($tab == "sms") {
            $sms_api = DB::table("sms_api")->get();
            return response()->json(view('super.company.sms_api', compact('user','sms_api'))->render());
        } else {
            return response()->json(view('super.company.access', compact('user'))->render());
        }
    }


    public function company()
    {
        return view("super.company.index");
    }

    public function clientList(Request $request)
    {
        $columns = array(
            3 => 'approve_date',
            4 => 'created_at'
        );
        
        $company_status = $request->company_status;

        $totalData = TmbdUsers::query();
        if ($company_status != 3) {
            $totalData = $totalData->where(["approval" => $company_status,"login_status" => 1]);
        } else {
            $totalData = $totalData->where(["login_status" => 2]);
        }
        $totalData = $totalData->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = TmbdUsers::query();
            if ($company_status != 3) {
                $posts = $posts->where(["approval" => $company_status,"login_status" => 1]);
            } else {
                $posts = $posts->where(["login_status" => 2]);
            }

            if ($limit >= 0) {
                $posts = $posts->offset($start)
                    ->limit($limit);
            }

            $posts = $posts->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = TmbdUsers::query()
                ->where('email_id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
            if ($limit >= 0) {
                $posts = $posts->offset($start)
                    ->limit($limit);
            }
            $posts = $posts->orderBy($order, $dir)
                ->get();

            $totalFiltered = TmbdUsers::query()
                ->where('email_id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();

        if (!empty($posts)) {
            $i = count($posts) + 1;

            foreach ($posts as $key => $post) {
                if ($dir == 'asc') {
                    $i = $key + 1;
                } else {
                    $i--;
                }
                $nestedData = array();

                $nestedData[] = $i;
                $nestedData[] = $post->name;
                $nestedData[] = $post->admin ? $post->admin->email : '' . "<br>" . $post->mobile;

                $nestedData[] = $post->approve_date;
                $nestedData[] = date("Y-m-d", strtotime($post->created_at));
                $nestedData[] = $post->admin->is_active == 1 && $post->approval == 1 ? "<span style='color:green;'>Active</span>" : ($post->approval == 2 ? "<span style='color:#fdb901'>Pending</span>" : "<span style='color:red'>Inactive</span>");

                $nestedData[] = '<div class="btn-group">
									<button type="button" id="' . $post->id . '" tab="profile" class="btn btn-info mymodal badge btn-sm">
										<i class="la la-user"></i>
									</button>
									<button type="button" id="' . $post->id . '" tab="sms" class="btn btn-warning mymodal badge btn-sm">
										<i class="la la-envelope"></i>
									</button>
									<button type="button" id="' . $post->id . '"  tab="access"  class="btn btn-success mymodal badge btn-sm">
										<i class="la la-key"></i>
									</button>
									
								</div>';
                // $nestedData[] = '<div class="btn-group dropleft">
                // 					<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                // 						Action
                // 					</button>
                // 					<div class="dropdown-menu">
                // 						<button class="dropdown-item" type="button"><i class="ft-user"></i> Profile</button>
                // 						<button class="dropdown-item" type="button"><i class="ft-lock"></i> Access</button>
                // 					</div>
                // 				</div>';


                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "offset" => [$start, $limit]
        );

        return response()->json($json_data);
    }
    function save_client(Request $request)
    {
        $users = User::find($request->id);
        $users->is_active = $request->status;
        if ($users->save()) {
            if ($request->approval) {
                $company = TmbdUsers::find($users->company_id);
                if ($company) {
                    $company->approval = $request->approval;
                    $company->approve_date = date("Y-m-d");
                    $company->save();
                }
            }
        }
        return response()->json($users);
    }
    function company_sms_api_set(Request $request)
    {
        $users = User::find($request->id);
        if($users->is_active ==1){
            $company = TmbdUsers::find($users->company_id);
            if($company){
                $company->sms_api_id = $request->sms_api_id;
                $company->non_masking_rate = $request->non_masking_rate;
                $company->masking_rate = $request->masking_rate;
                $company->save();
                return response()->json(["status"=>true,"message"=>"SMS API set successfully"]);
            }
            return response()->json(["status"=>false,"message"=>"Company not found"]);
        }        
        return response()->json(["status"=>false,"message"=>"Company is not active"]);
    }
    function reset_password(Request $request)
    {
        $password=rand(0,9).rand(11,99).rand(99,999);
        $users = User::find($request->id);
        $users->password = Hash::make($password);
        $users->save();
        return response()->json([$users->save(),$password]);
    }
}
