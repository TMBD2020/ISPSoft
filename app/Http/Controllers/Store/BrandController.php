<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductBrands;
use Auth;

class BrandController extends Controller
{
    protected $product_brands = "product_brands";

    public function productBrandSave(Request $request)
    {
        if($request->action==1){
            $result = ProductBrands::query()->insert(
                [
                    "company_id"=>\Settings::company_id(),
                    "brand_name"     => $request->brand_name,
                    "brand_description"     => $request->brand_description,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "brand_name"     => $request->brand_name,
                "brand_description"     => $request->brand_description,
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result =ProductBrands::query()->whereId($request->id)->update($data);
        }

        return response()->json($result);
    }

    public function productBrandList(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'brand_name',
            2 =>'brand_description',
        );

        $totalData = ProductBrands::query()
            ->where('company_id',\Settings::company_id())
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = ProductBrands::query()
                ->where('company_id',\Settings::company_id())
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   ProductBrands::query()->where('id','LIKE',"%{$search}%")
                ->where('company_id',\Settings::company_id())
                ->orWhere('brand_name', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  ProductBrands::query()->where('id','LIKE',"%{$search}%")
                ->where('company_id',\Settings::company_id())
                ->orWhere('brand_name', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->brand_name;
                $nestedData[] = $post->brand_description;
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

    public function productBrandUpdate(Request $request)
    {
        $result =  ProductBrands::query()->whereId($request->id)->first();
        return response()->json($result);

    }

    public function productBrandDelete(Request $request)
    {
        return response()->json(ProductBrands::find($request->id)->delete());
    }
    public function productBrandShow()
    {
       return response()->json(ProductBrands::all());
    }
}
