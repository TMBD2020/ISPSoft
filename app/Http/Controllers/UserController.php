<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserRegistered;
use Auth;
use Validator;
use Illuminate\Validation\ValidationException;
use DB;
use App\Models\User;
use Hash;
use App\Models\TmbdUsers;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Models\SmsTemplates;
use App\Models\Employees;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    
//     public function test(Request $request)
//     {
//         //        return ['email' => $request->email, 'password' => $request->password];
// //        exit();
//         if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1], ($request->remember) ? true : false)) {
//             return ['email' => $request->email, 'password' => $request->password, "status" => true];
//         } else {
//             return ['email' => $request->email, 'password' => $request->password, "status" => false];
//         }
//     }

    public function authenticate(Request $request)
    {
        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'is_active' => 1
            ], ($request->remember) ? true : false)
        ) {
            if (auth()->user()->user_type == "super") {
                return redirect('super');
            }
            if(auth()->user()->admin->approval==1){
                return redirect('client');
            }
            auth()->loginUsingId(555);
            return redirect('client');
        } else {
            throw ValidationException::withMessages([
                "email" => [trans('auth.failed')],
            ]);
        }
    }

    public function index()
    {
        $user_id = array();
        $current_user_list = Auth::user()
            ->where(["company_id" => \Settings::company_id()])
            ->where("user_type", "emp")
            ->where("is_admin", "1")
            ->get();
        if ($current_user_list):
            foreach ($current_user_list as $item) {
                $user_id[] = $item->id;
            }
        endif;
        if ($user_id):
            $employees = DB::table($this->employees)
                ->where(["company_id" => \Settings::company_id()])->whereNotIn("auth_id", $user_id)->get();
        else:
            $employees = Employees::company()->get();
        endif;
        $roles = Role::query()
            ->where(["company_id" => \Settings::company_id()])
            //->where("id","<>","1")
            ->get();
        return view("back.admin.list", compact("employees", "roles"));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user' => ['required'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    public function save_user(Request $request)
    {
        $validate = $this->validator($request->all());
        if (!$validate->fails()) {
            $user = User::find($request->user);
            $user->update([
                'is_admin' => $request->status
            ]);
            $user->syncRoles($request->roles);
            if ($user):
                echo 1;
            else:
                echo 0;
            endif;
            //
        } else {
            echo 0;
        }
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
            ->where(["company_id" => \Settings::company_id()])
            //->where(["is_admin"=>1])
            ->whereIn("user_type", ['admin', 'emp'])
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = User::query()
                ->where(["company_id" => \Settings::company_id()])
                // ->where("is_admin",1)
                ->whereIn("user_type", ['admin', 'emp'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = User::query()
                ->where(["company_id" => \Settings::company_id()])
                ->where('user_id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                // ->where("is_admin",1)
                ->whereIn("user_type", ['admin', 'emp'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = User::query()
                ->where(["company_id" => \Settings::company_id()])
                ->where('user_id', 'LIKE', "%{$search}%")
                //->where("is_admin",1)
                ->whereIn("user_type", ['admin', 'emp'])
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();

        if (!empty($posts)) {
            foreach ($posts as $key => $post) {
                $nestedData = array();

                $nestedData[] = $post->id;
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
                <span class="la la-cog"></span></button>
                <button id="' . $post->id . '" class="deleteData btn btn-info btn-sm badge">
                <span class="la la-key"></span></button>
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

        echo json_encode($json_data);
    }

    public function adminUpdate(Request $request)
    {
        $user = User::find($request->id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('id')->all();

        return response()->json([$user, $userRole]);
    }

    public function empList(Request $request)
    {
        $user_id = array();
        $current_user_list = Auth::user()
            ->where(["company_id" => \Settings::company_id()])
            ->where("user_type", "emp")
            ->where("is_admin", "1")
            ->get();
        if ($current_user_list):
            foreach ($current_user_list as $item) {
                $user_id[] = $item->id;
            }
        endif;
        if ($user_id):
            $employees = DB::table($this->employees)
                ->where(["company_id" => \Settings::company_id()])->whereNotIn("auth_id", $user_id)->get();
        else:
            $employees = DB::table($this->employees)
                ->where(["company_id" => \Settings::company_id()])->get();
        endif;

        echo json_encode($employees);
    }

    public function adminDelete(Request $request)
    {
        $password = rand(0, 9) . rand(11, 99) . rand(99, 999);
        $result = DB::table("users")
            ->whereId($request->id)
            ->update([
                'password' => Hash::make($password),
            ]);
        if ($result) {
            return response()->json($password);
        } else {
            return response()->json(false);
        }
    }

    public function showChangePasswordForm()
    {
        return view('auth.changepassword');
    }

    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();


        // Mail::send('emails.password_reset',$user->toArray(),function($msg){
        //     $msg->from("support@techmakersbd.com","ImaxQ")
        //         ->to("aliakbarctg@yahoo.com","Ali Akbar")
        //         ->subject("Password has been reset");
        // });


        return redirect()->back()->with("success", "Password changed successfully !");
    }
    public function changePhoto(Request $request)
    {
        if ($request->hasFile("new_photo")) {
            $old_pic = $request->old_photo;
            $file = $request->file("new_photo");
            $name = Auth::user()->email . "_" . time() . "." . $file->guessClientExtension();
            $upload = $request->new_photo->move(public_path('app-assets/images/admin/'), $name);
            if ($upload) {
                if ($old_pic) {
                    if (file_exists(public_path($old_pic))) {
                        unlink(public_path($old_pic));
                    }
                }
                $user = Auth::user();
                $user->photo = 'app-assets/images/admin/' . $name;
                $user->save();
                return redirect()->back()->with("success2", "Photo changed successfully!");
            } else {
                return redirect()->back()->with("error2", "Photo cannot uploaded!");
            }
        } else {
            return redirect()->back()->with("error2", "Photo cannot left blank!");
        }
    }

    public function changeThemeColors(Request $request)
    {
        $user_id = Auth::user()->company_id;

        $data["header_bg_color_1"] = $request->header_bg_color_1;
        $data["header_bg_color_2"] = $request->header_bg_color_2;
        $data["sidebar_bg_color"] = $request->sidebar_bg_color;
        $data["sidebar_text_color"] = $request->sidebar_text_color;
        $data["body_bg_color"] = $request->body_bg_color;
        $data["card_bg_color"] = $request->card_bg_color;
        $data["button_bg_color"] = $request->button_bg_color;
        $data["button_text_color"] = $request->button_text_color;

        $check = DB::table($this->themecolors)
            ->where(["company_id" => \Settings::company_id()])->where("company_id", $user_id)->count();
        if ($check > 0) {
            $result = DB::table($this->themecolors)->where("company_id", $user_id)->update($data);
        } else {
            $result = DB::table($this->themecolors)->insert($data);
        }
        if ($result == true) {
            return redirect()->back()->with("success3", "Colors saved successfully!");
        } else {
            return redirect()->back()->with("error3", "Colors cannot saved. Try again!");
        }
    }

    public function registerTmbdUser()
    {

        return view("auth.register");
    }
    public function registerTmbdUserSave(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required',
            'password' => 'required|min:6',
        ]);

        $tmbduser = new TmbdUsers();

        $tmbduser->name = $request->name;
        $tmbduser->mobile = $request->mobile;
        $tmbduser->email_id = $request->email;
        $tmbduser->save();
        if ($tmbduser->id) {
            $tmbduser = TmbdUsers::find($tmbduser->id);
            $serial = $tmbduser->id + 100;
            $tmbduserId = "TM" . $serial;
            $tmbduser->reg_no = $tmbduserId;
            $tmbduser->save();


            $user = new User();
            $user->name = $request->name;
            $user->user_id = $tmbduserId;
            $user->company_id = $tmbduser->id;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->is_admin = 1;
            $user->user_type = "admin";
            $user->ref_role_id = "0";
            $user->activation_date = date("Y-m-d");
            $user->is_active = 1;
            $user->save();

            //sms template create
            if ($user) {



                //  $role->syncPermissions($request->permission);

                if (config('constants.sms')) {
                    foreach (config('constants.sms') as $key => $row) {
                        SmsTemplates::create([
                            "company_id" => $user->company_id,
                            "template_name" => $row['name'],
                            "template_text" => $row['text'],
                            "template_type" => $row['type'],
                            "template_cat" => $row['cat'],
                            "keyword" => $row['keyword'],
                            "temp_status" => $row['status'],
                            "system" => 1
                        ]);
                    }
                }
                auth()->login($user);

                // $role = Role::create(["name" => "Administrator", "company_id" => $tmbduser->id]);
                // $role->syncPermissions(Permission::whereNotIn("id",[83,86,88])->get()->pluck("id"));
                // $user->syncRoles($role->id);
            }



            // event(new UserRegistered($user));
            return redirect("/client");
        }


        //return view("auth.register");
    }
}
