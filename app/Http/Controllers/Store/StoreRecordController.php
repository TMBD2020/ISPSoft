<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRecords;
use Auth;
use DB;
class StoreRecordController extends Controller
{

    public function index(){
        return view("back.store.store_record");
    }

    public function store_record(Request $request)
    {
        $columns = array(
            0 =>'ref_product_id',
            1 =>'ref_product_id',
            2 =>'product_in',
            3 =>'product_out',
            4 =>'product_damage',
            5 =>'product_available',
        );

        $totalData = StoreRecords::query()->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = StoreRecords::query()
                ->select(DB::raw("
            ref_product_id,
            sum(product_out) as stock_out,
            sum(product_in) as stock_in,
            sum(product_damage) as stock_damage
            "))
                ->where('company_id',\Settings::company_id())
                ->groupBy(["ref_product_id"])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   StoreRecords::query()
                ->select(DB::raw("
            ref_product_id,
            sum(product_out) as stock_out,
            sum(product_in) as stock_in
            "))
                ->where('company_id',\Settings::company_id())
                ->where('id','LIKE',"%{$search}%")
                ->offset($start)
                ->groupBy(["ref_product_id"])
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  StoreRecords::query()
                ->where('id','LIKE',"%{$search}%")
                ->where('company_id',\Settings::company_id())
                ->groupBy(["ref_product_id"])
                ->count();
        }


        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
                $nestedData[] = $post->ref_product_id;
                $nestedData[] = $post->ref_product_id;
                $nestedData[] = $post->stock_in-($post->stock_out+$post->stock_damage);
                $nestedData[] = $post->stock_in;
                $nestedData[] = $post->stock_out;
                $nestedData[] = $post->stock_damage;

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
}
