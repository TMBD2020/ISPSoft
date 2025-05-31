<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsTemplates;
use DB;
use Auth;

class SMSTemplateController extends Controller
{
    protected $sms_template = "sms_templates";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
//        foreach (config('constants.sms') as $key=>$row ) {
//            SmsTemplates::create([
//                "company_id"     => 1,
//                "template_name"  => $row['name'],
//                "template_text"  => $row['text'],
//                "template_type"  =>  $row['type'],
//                "template_cat"   => $row['cat'],
//                "keyword"   =>  $row['keyword'],
//                "temp_status"    => $row['status'],
//                "system"         => 1
//            ]);
//        }
//dd($smsT);
        return view("back.sms.sms_template");
    }

    public function save_SMSTemplate(Request $request)
    {
        if($request->action==1){
            $result = SmsTemplates::create(
                [
                    "template_name"     => $request->template_name,
                    "template_text"     => $request->template_text,
                     "template_cat"     => $request->template_cat,
                ]
            );
        }else{
            $data =array(
                "template_name"     => $request->template_name,
                "template_text"     => $request->template_text,
                 "template_cat"     => $request->template_cat,
            );
            $result = SmsTemplates::query()->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function SMSTemplateList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'template_name',
            2 =>'template_text',
            3 =>'template_cat',
        );

        $totalData = SmsTemplates::company()->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = SmsTemplates::company()->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   SmsTemplates::company()->where('id','LIKE',"%{$search}%")
                ->orWhere('template_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  SmsTemplates::company()->where('id','LIKE',"%{$search}%")
                ->orWhere('template_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $key=>$post)
            {
                $nestedData = array();

                $nestedData[] = $key+1;
                $nestedData[] = $post->template_name;
                $nestedData[] = $post->template_text;
                $nestedData[] = $post->template_cat;
                $nestedData[] = $post->temp_status;
                $action =   '<div class="btn-group align-top" role="group">
                <button id="'.$post->id. '" class="update btn btn-primary btn-sm badge">
            <span class="ft-edit"> Edit</span></button>';
                if($post->temp_status==2){
                    $action .='
            <button  id=' .$post->id. ' class="deleteData btn btn-danger btn-sm badge">
            <span class="ft-delete"> Delete</span></button>
            </div>';
                }
                $nestedData[]=$action;

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

    public function SMSTemplateUpdate(Request $request)
    {
        $result =  SmsTemplates::find($request->id);
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function SMSTemplateDelete(Request $request)
    {
        $result = SmsTemplates::query()->whereId($request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

}
