<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class SalarySettingController extends Controller
{

    protected  $salary_settings = "salary_distribution_setting";

    public function index()
    {
        return view("back.settings.salary.salary_distribution");
    }

    public function save_Salary_Setting(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->salary_settings)->insert(
                [
                    "company_id"         => \Settings::company_id(),
                    "title"         => $request->title,
                    "percentage"    => $request->percentage,
                    "created_at"    => date("Y-m-d H:i:s"),
                ]
            );
        }else{
            $data =array(
                "title"         => $request->title,
                "percentage"    => $request->percentage,
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->salary_settings)->whereId($request->id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function DistributionList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'title',
            2 =>'percentage',
        );

        $totalData = DB::table($this->salary_settings)->where("company_id",\Settings::company_id())->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->salary_settings)
                ->where("company_id",\Settings::company_id())
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->salary_settings)
            ->where("company_id",\Settings::company_id())
                ->where('title','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->salary_settings)
                ->where('title','LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            $count = 0;
            foreach ($posts as $post)
            {
                $nestedData = array();
                $count += $post->percentage;
                $nestedData[] = $post->id;
                $nestedData[] = $post->title;
                $nestedData[] = $post->percentage;
                $data[] = $nestedData;
            }
            $other = 100-$count;
            if($other>0){
                $nestedData = array();
                $nestedData[] = 0;
                $nestedData[] = "Other";
                $nestedData[] = $other;
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

    public function SalarySettingUpdate(Request $request)
    {
        $result =  DB::table($this->salary_settings)->where("id",$request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function availablePercent()
    {
        $result =  DB::table($this->salary_settings)
            ->select(DB::raw("sum(percentage) as percentage"))
            ->where("company_id",\Settings::company_id())
            ->first();
        if($result){
            echo 100-$result->percentage;
        }else{
            echo 100;
        }
    }

    public function SalarySettingDelete(Request $request)
    {
        $result = DB::table($this->salary_settings)->whereId($request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
}
