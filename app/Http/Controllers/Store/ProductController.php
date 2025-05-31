<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreProducts;
use App\Models\Employees;
use App\Models\Vendors;
use Auth;
use DB;

class ProductController extends Controller
{

    public function index(){
        $employees =Employees::query()->where("is_active",1)
            ->where('company_id',\Settings::company_id())->get();
        $vendors = Vendors::query()
            ->where('company_id',\Settings::company_id())->get();
        $stock_products = StoreProducts::query()->where('company_id',\Settings::company_id())->get();
        return view("back.store.product", compact("employees","vendors","stock_products"));
    }


    public function productSave(Request $request)
    {
        if($request->action==1){
            $result = StoreProducts::query()->insert(
                [
                    "company_id"=>\Settings::company_id(),
                    "product_name"     => $request->product_name,
                    "brand_id"     => $request->brand_id,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "product_name"     => $request->product_name,
                "brand_id"     => $request->brand_id,
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = StoreProducts::query()->whereId($request->id)->update($data);
        }

        return response()->json($result);
    }

    public function productList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'product_name',
            2 =>'brand_id'
        );

        $totalData = StoreProducts::query()->where('company_id',\Settings::company_id())->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = StoreProducts::query()
                ->where('company_id',\Settings::company_id())
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   StoreProducts::query()
                ->where('id','LIKE',"%{$search}%")
                ->where('company_id',\Settings::company_id())
                ->orWhere('product_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  StoreProducts::query()
                ->where('id','LIKE',"%{$search}%")
                ->where('company_id',\Settings::company_id())
                ->orWhere('product_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->product_name;
                $nestedData[] = $post->brands->brand_name;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );


        return response()->json($json_data);
    }

    public function productUpdate(Request $request)
    {
        $result =  StoreProducts::query()->whereId($request->id)->first();
        return response()->json($result);
    }

    public function productDelete(Request $request)
    {
        $result = StoreProducts::find($request->id)->delete();
        return response()->json($result);
    }
    public function productListShow()
    {
        $result = StoreProducts::query()
            ->where('company_id',\Settings::company_id())
            ->orderBy("product_name","asc")->get();
        return response()->json($result);
    }
}
