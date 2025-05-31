<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\TmbdUsers;

class SMSAPIController extends Controller
{
    protected $sms_api = "sms_api";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        return view("super.sms.api");
    }

    public function saveSMSAPI(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->sms_api)->insert(
                [
                    "api_name"      => $request->api_name,
                    "api_sender"    => $request->api_sender,
                    "api_url"       => $request->api_url,
                    "api_username"  => $request->api_user_name,
                    "api_password"  => $request->api_pass_word,
                    "api_token"  => $request->api_token,
                    "api_status"    => $request->api_status,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
            // if($request->api_default==1){
            //     $id = DB::getPdo()->lastInsertId();
            //     DB::table($this->sms_api)->whereNotIn("id",[$id])->update([
            //         "api_default"     => 0,
            //     ]);
            //     $tmbdUsers= TmbdUsers::find(\Settings::company_id());
            //     $tmbdUsers->sms_api=json_encode(
            //         [
            //             "sender"    => $request->api_sender,
            //             "url"       => urlencode($request->api_url),
            //             "username"  => $request->api_user_name,
            //             "password"  => $request->api_pass_word,
            //             "token"  => $request->api_token,
            //         ]
            //     );
            //     $tmbdUsers->save();
            // }
        }else{
            $data =array(
                "api_name"      => $request->api_name,
                "api_sender"    => $request->api_sender,
                "api_url"       => $request->api_url,
                "api_username"  => $request->api_user_name,
                "api_status"    => $request->api_status,
                "api_token"    => $request->api_token,
                "api_password"  => $request->api_pass_word,
                "updated_at"    => date("Y-m-d H:i:s")
            );
           
            $result = DB::table($this->sms_api)->whereId($request->id)->update($data);

            // if($request->api_default==1){
            //     DB::table($this->sms_api)->whereNotIn("id",[$request->id])->update([
            //         "api_default"     => 0,
            //     ]);
                
            //     $tmbdUsers= TmbdUsers::find(\Settings::company_id());
            //     $tmbdUsers->sms_api=json_encode(
            //         [
            //             "sender"    => $request->api_sender,
            //             "url"       => urlencode($request->api_url),
            //             "username"  => $request->api_user_name,
            //             "password"  => $request->api_pass_word,
            //             "token"  => $request->api_token,
            //             "cost"  => $request->sms_rate,
            //         ]
            //     );
            //     $tmbdUsers->save();
            // }
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function SMSAPIList(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'api_name',
            2 =>'api_sender',
            3 =>'api_username'
        );

        $totalData = DB::table($this->sms_api)->count();

        $totalFiltered = $totalData;

        try{
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
    
            if(empty($request->input('search.value')))
            {
                $posts = DB::table($this->sms_api)->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            }
            else {
                $search = $request->input('search.value');
    
                $posts =   DB::table($this->sms_api)
                ->where('id','LIKE',"%{$search}%")
                    ->orWhere('api_name', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
    
                $totalFiltered =  DB::table($this->sms_api)->where('id','LIKE',"%{$search}%")
                    ->orWhere('api_name', 'LIKE',"%{$search}%")
                    ->count();
            }
    
            $data = array();
            if(!empty($posts))
            {
                foreach ($posts as $post)
                {
                    $nestedData = array();
    
                    $nestedData[] = $post->id;
                    $nestedData[] = $post->api_name;
                    $nestedData[] = $post->api_sender;
                    $nestedData[] = $post->api_username;
                    $nestedData[] = '<div class="btn-group align-top" role="group">
                                        <button id="' .$post->id. '" class="update btn btn-primary btn-sm badge">
                                        <span class="ft-edit"></span></button>
                                        <!--button  id=' .$post->id. ' class="deleteData btn btn-danger btn-sm badge">
                                        <span class="ft-delete"></span></button-->
                                        </div>';
                    $data[] = $nestedData;
                }
            }
    
            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
                "q"            =>  $request->input('order')
    
            );
    
            echo json_encode($json_data);
        } catch(\Exception $e){
            $json_data = array(
               
                "q"=>  $request->input('order.0.column')
    
            );
        }
    }

    public function SMSAPIUpdate(Request $request)
    {
        $result =  DB::table($this->sms_api)->whereId($request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function SMSAPIDelete(Request $request)
    {
        $result = DB::table($this->sms_api)->whereId($request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function defaultApi()
    {
        $result = DB::table($this->sms_api)->where("api_default",1)->first();
        if($result){
            return $result->id;
        }
        return null;
    }

}
