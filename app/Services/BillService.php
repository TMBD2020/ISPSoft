<?php
namespace App\Services;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Bills;
use App\Models\Clients;
use App\Models\Packages;
use App\Models\BillReceives;
use App\Models\SmsHistory;
use App\Services\TicketService;

class BillService
{
    private $clientType = 1;
    public function clientAllBill22($request)
    {
        $columns = array(
            0 => 'id',
            1 => 'connection_mode',
        );

        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));

        $totalData = Bills::query()
            ->select(
                DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                //DB::raw("sum(receive_amount) as rcv_amount"),
                DB::raw("sum(discount_amount) as dis_amount"),
                "client_id"
            );
            if (auth()->user()->can('isp-only-responsible-bill')) {
                $totalData = $totalData->whereHas('client', function ($query) {
                    $query->where('billing_responsible', auth()->user()->id);
                });
            }
            $totalData = $totalData ->where("company_id", \Settings::company_id())
            ->where("bill_approve", 1)
            ->where("client_type", $this->clientType)
            ->groupBy('client_id')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Bills::query()
                ->select(
                    DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                    //DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("sum(discount_amount) as dis_amount"),
                    "client_id",
                    "package_title",
                    "package_amount"
                )->where("company_id", \Settings::company_id());
            if (auth()->user()->can('isp-only-responsible-bill')) {
                $posts = $posts->whereHas('client', function ($query) {
                    $query->where('billing_responsible', auth()->user()->id);
                });
            }
            $posts = $posts->where("bill_approve", 1)
                ->where("client_type", $this->clientType)
                ->groupBy('client_id')
                ->orderBy("client_id", $dir);
            $posts = $posts->get();
        } else {
            $search = trim($request->input('search.value'));
            $clinets = Clients::query()
                ->select("auth_id")
                ->where("company_id", \Settings::company_id())
                ->Where('client_id', "like", "%{$search}%")
                ->orWhere('client_name', 'like', "%{$search}%")
                ->orWhere('cell_no', 'LIKE', "%{$search}%")
                ->get();


            $posts = Bills::query()
                ->select(
                    DB::raw("(sum(payable_amount)-sum(receive_amount)-sum(discount_amount)) as due_amount"),
                    // DB::raw("sum(receive_amount) as rcv_amount"),
                    "client_id",
                    "package_title",
                    "package_amount"
                )
                ->where("bill_approve", 1)
                ->where("client_type", $this->clientType);
                if (auth()->user()->can('isp-only-responsible-bill')) {
                    $posts = $posts->whereHas('client', function ($query) {
                        $query->where('billing_responsible', auth()->user()->id);
                    });
                }
            if (count($clinets) > 0) {
                $ids = [];
                foreach ($clinets as $clinetsss) {
                    $ids[] = $clinetsss->auth_id;
                }
                $posts = $posts->whereIn("client_id", $ids)
                    ->where("company_id", \Settings::company_id());
            }
            $posts = $posts->groupBy('client_id')->orderBy("client_id", $dir)->get();
            if (count($clinets) == 0) {
                $posts = [];
            }
            $totalFiltered = count($posts);
        }

        $data = array();
        if (!empty($posts)) {
            $sl = count($posts);
            foreach ($posts as $key => $post) {
                $clinet = Clients::query()->where("auth_id", $post->client_id);
                if ($request->date_filter) {
                    $clinet = $clinet->whereBetween("termination_date", [$request->date_from, $request->date_to]);
                }
                $clinet = $clinet->first();
                if ($clinet) {

                    $nestedData = array();
                    if ($dir == "asc") {
                        $nestedData[] = $key + 1;
                    } else {
                        $nestedData[] = $sl;
                        $sl--;
                    }
                    $clientStatus = "#2ebd2e";
                    $clientStatuTitles = "Active";
                    if ($clinet->connection_mode == 0) {
                        $clientStatus = "red";
                        $clientStatuTitles = "Inactive";
                    } elseif ($clinet->connection_mode == 2) {
                        $clientStatus = "yellow";
                        $clientStatuTitles = "Locked";
                    }
                    $nestedData[] = "<font class='pull-left'>" . $clinet->client_id . "<br>" . $clinet->client_name . "<br>" . $post->cell_no . "</font><b title='$clientStatuTitles' style='width:10px;border-radius:50%;height: 10px; background: $clientStatus' class='pull-right'>&nbsp;</b>";
                    $nestedData[] = $clinet->cell_no;
                    $nestedData[] = $clinet->termination_date;
                    //$nestedData[] = $clinet->termination_date;
                    $nestedData[] = $clinet->package->package_name . "<br><span class='taka'>&#2547;.</span>" . $clinet->package->package_price;
                    $nestedData[] = number_format($clinet->permanent_discount, 2);
                    $payable = number_format($post->due_amount, 2);
                    $nestedData[] = $payable > 0 ? "<b style='color:red;'>" . $payable . "</b>" : $payable;
                    // $nestedData[] = number_format($post->rcv_amount, 2);
                    $action = '<div class="btn-group align-top" role="group">';
                    if (auth()->user()->can('isp-bill-collect')) { //bill receive access
                        $action .= '<button  ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Collect bill" class="open btn btn-primary btn-sm badge">
                                                    <i class="la la-money"></i></button>';
                    }

                    $action .= ' <button ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Send SMS" class="dueSMS btn btn-warning btn-sm badge">
                                    <i class="la la-envelope"></i></button>
                                    <button ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Commitment Date" class="commitDate btn btn-success btn-sm badge">
                                    <i class="la la-calendar"></i></button>';

                    if (auth()->user()->can('set-isp-bill-responsible-person')) {
                        $action .= '<button style="'.( !empty($clinet->billing_responsible)?"background:green":"background:red").'" id="' . $post->client_id . '" title="Responsible Person '.( !empty($clinet->billing_responsible)?"Set":"Not Set").'" class="responsiblep btn btn-dark btn-sm badge">
                                                    <i class="la la-user"></i></button>';
                    }
                    if (auth()->user()->can('isp-bill-collect')) { //bill receive access
                    $action .= '<button id="' . $post->client_id . '" title="Create Other Bill" class="otherBill btn btn-secondary btn-sm badge">
                                    <i class="la la-credit-card"></i></button>';
                    }
                    $action .= '<button id="' . $post->client_id . '" title="View" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
                                </div>';
                    $nestedData[] = $action;
                    $data[] = $nestedData;
                }


            }
        }

        return ["totalFiltered" => $totalFiltered, "totalData" => $totalData, "data" => $data];
    }
    public function clientAllBill($request)
    {
        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));
        $limit = $request->input('length');
        $start = $request->input('start');
        $company_id=\Settings::company_id();
        $totalData=Clients::company();
        if ($request->date_filter) {
            if($request->filter_type==1){
                $totalData= $totalData->whereBetween("termination_date", [$request->date_from, $request->date_to]);
            }
            elseif($request->filter_type==2){
                $request->date_from=date("d", strtotime($request->date_from));
                $request->date_to=date("d", strtotime($request->date_to));
                $totalData= $totalData->whereBetween("payment_dateline", [$request->date_from, $request->date_to]);       
            }elseif($request->filter_type==3){
                $request->date_from=date("d", strtotime($request->date_from));
                $request->date_to=date("d", strtotime($request->date_to));
                $totalData= $totalData->whereBetween("billing_date", [$request->date_from, $request->date_to]);          
            }           
        }
        
        $totalData= $totalData->count();
        $sql="
        
            SELECT debit, credit, q.client_id, c.client_id username, c.client_name,c.cell_no, c.connection_mode, c.billing_responsible,p.package_name, p.package_price, IFNULL(c.permanent_discount,0) permanent_discount,c.termination_date,c.billing_date,c.payment_dateline FROM (

                SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE company_id =$company_id  GROUP BY client_id
                
                UNION ALL 
                
                SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit, client_id FROM bill_receives WHERE company_id = $company_id  GROUP BY client_id

            ) AS q 
            INNER JOIN clients c ON c.auth_id=q.client_id AND c.company_id=$company_id
            LEFT JOIN packages p ON p.id=c.package_id AND p.company_id=$company_id 
            where 1
        ";
        if (auth()->user()->can('isp-only-responsible-bill')) { 
            $sql.=" and c.billing_responsible = ".auth()->user()->id;          
        }
        if ($request->date_filter) {
            if($request->filter_type==1){
                $sql.=" and c.termination_date Between '".$request->date_from."' and '".$request->date_to."'";          
            }
            elseif($request->filter_type==2){
                $request->date_from=date("d", strtotime($request->date_from));
                $request->date_to=date("d", strtotime($request->date_to));
                $sql.=" and c.payment_dateline Between '".$request->date_from."' and '".$request->date_to."'";          
            }elseif($request->filter_type==3){
                $request->date_from=date("d", strtotime($request->date_from));
                $request->date_to=date("d", strtotime($request->date_to));
                $sql.=" and c.billing_date Between '".$request->date_from."' and '".$request->date_to."'";          
            }           
        }
        if (!empty($request->input('search.value'))) {
            $search = trim($request->input('search.value'));
            $sql.=" and (c.client_id like '%".$search."%' or c.client_name like '%".$search."%' or c.cell_no like '%".$search."%')"; 
        }
        $sql.="  ORDER BY c.client_id ASC limit $limit offset $start";
        $posts=  DB::select( $sql);
        
        $data = array();
        if (!empty($posts)) {
            $sl = count($posts);
            foreach ($posts as $key => $post) {               

                    $post->due_amount=$post->debit-$post->credit;

                    $nestedData = array();
                   
                        $nestedData[] = $key + 1;
                  
                    $clientStatus = "#2ebd2e";
                    $clientStatuTitles = "Active";
                    if ($post->connection_mode == 0) {
                        $clientStatus = "red";
                        $clientStatuTitles = "Inactive";
                    } elseif ($post->connection_mode == 2) {
                        $clientStatus = "yellow";
                        $clientStatuTitles = "Locked";
                    }
                    $nestedData[] = "<font class='pull-left'>" . $post->client_id . "<br><b>" . $post->client_name . "</b><br>" . $post->cell_no . "</font><b title='$clientStatuTitles' style='width:10px;border-radius:50%;height: 10px; background: $clientStatus' class='pull-right'>&nbsp;</b>";
       
                    $nestedData[] = $post->payment_dateline;
                    $nestedData[] = $post->billing_date;
                    $nestedData[] = $post->termination_date;
                    $nestedData[] = $post->package_name . "<br><span class='taka'>&#2547;.</span>" . $post->package_price."<br>P.Discount: ".$post->permanent_discount;
             
                    $payable = number_format($post->due_amount, 2);
                    if($post->due_amount>0){
                        $nestedData[] = "<b style='color:red;'>".$payable."</b>";
                    } 
                    elseif($post->due_amount<0){
                        $nestedData[] = "<b style='color:green;'>(".(number_format(abs($post->due_amount), 2)).")</b>";
                    } 
                    else{
                        $nestedData[] = $payable;
                    }
                    // $nestedData[] = number_format($post->rcv_amount, 2);
                    $action = '<div class="btn-group align-top" role="group">';
                    if (auth()->user()->can('isp-bill-collect')) { //bill receive access
                        $action .= '<button  id="' . $post->client_id . '" title="Collect bill" class="open btn btn-primary btn-sm badge">
                                                    <i class="la la-money"></i></button>';
                    }

                    $action .= ' <button ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Send SMS" class="dueSMS btn btn-warning btn-sm badge">
                                    <i class="la la-envelope"></i></button>
                                    <button ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Commitment Date" class="commitDate btn btn-success btn-sm badge">
                                    <i class="la la-calendar"></i></button>';

                    if (auth()->user()->can('set-isp-bill-responsible-person')) {
                        $action .= '<button style="'.( !empty($post->billing_responsible)?"background:green":"background:red").'" id="' . $post->client_id . '" title="Responsible Person '.( !empty($post->billing_responsible)?"Set":"Not Set").'" class="responsiblep btn btn-dark btn-sm badge">
                                                    <i class="la la-user"></i></button>';
                    }
                    if (auth()->user()->can('isp-bill-collect')) { //bill receive access
                    $action .= '<button id="' . $post->client_id . '" title="Create Other Bill" class="otherBill btn btn-secondary btn-sm badge">
                                    <i class="la la-credit-card"></i></button>';
                    }
                    $action .= '<button id="' . $post->client_id . '" title="View" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
                                </div>';
                    $nestedData[] = $action;
                    $data[] = $nestedData;

            }
        }
       

       
        return [
            "data" => $data,
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval(count($posts)),
            "query"=>$sql,
       ];
    }

    public function allCollectedBill($request)
    {
        $columns = [
            0 => 'id',
            1 => 'client_name',
        ];


        $date_from = date("Y-m-d", strtotime($request->date_from));
        $date_to = date("Y-m-d", strtotime($request->date_to));

        $totalData = Bills::query()
            ->select(
                DB::raw("sum(receive_amount) as rcv_amount"),
                DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                "client_id"
            );
            if (auth()->user()->can('isp-only-responsible-bill')) {
                $totalData = $totalData->whereHas('client', function ($query) {
                    $query->where('billing_responsible', auth()->user()->id);
                });
            }
            // ->whereBetween("receive_date",[$date_from,$date_to])
            $totalData = $totalData ->where("company_id", \Settings::company_id())
            ->where("bill_approve", 1)
            ->where("client_type", $this->clientType)
            ->groupBy('client_id')
            ->count();

        $totalFiltered = $totalData;


        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Bills::query()
                ->select(
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                    "client_id"
                );
                if (auth()->user()->can('isp-only-responsible-bill')) {
                    $posts = $posts->whereHas('client', function ($query) {
                        $query->where('billing_responsible', auth()->user()->id);
                    });
                }
                $posts = $posts->where("bill_approve", 1)
                // ->whereBetween("receive_date",[$date_from,$date_to])
                ->where("company_id", \Settings::company_id())
                ->where("client_type", $this->clientType)
                ->groupBy('client_id')
                //->havingRaw("rcv_amount>0")
                ->get();
        } else {
            $search = $request->input('search.value');


            $clinets = Clients::query()
                ->select("auth_id")
                ->orWhere('client_id', '=', "$search")
                ->orWhere('client_name', 'like', "%{$search}%")
                ->where("company_id", \Settings::company_id())
                ->orderBy($order, $dir)
                ->get();

            $posts = Bills::query()
                ->select(
                    DB::raw("sum(receive_amount) as rcv_amount"),
                    DB::raw("(sum(discount_amount)+sum(permanent_discount_amount)) as dis_amount"),
                    "client_id"
                )->where("company_id", \Settings::company_id())
                
                ->where("bill_approve", 1)
                ->where("client_type", $this->clientType);
            // ->whereBetween("receive_date",[$date_from,$date_to]);
            if (auth()->user()->can('isp-only-responsible-bill')) {
                $posts = $posts->whereHas('client', function ($query) {
                    $query->where('billing_responsible', auth()->user()->id);
                });
            }
            if (count($clinets) > 0) {
                $ids = [];
                foreach ($clinets as $clinetsss) {
                    $ids[] = $clinetsss->auth_id;
                }
                $posts = $posts->whereIn("client_id", $ids);
            }

            $posts = $posts->groupBy('client_id')
                //->havingRaw("rcv_amount>0")
                ->get();

            $totalFiltered = count($posts);

        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $clinet = Clients::where("auth_id", $post->client_id)->first();
                $nestedData = array();
                $nestedData[] = $post->client_id;
                $nestedData[] = $clinet->client_id . "<br>" . $clinet->client_name;
                $nestedData[] = $clinet->cell_no;
                $nestedData[] = $clinet->package->package_name . "/" . $clinet->package->package_price;
                $nestedData[] = number_format($post->dis_amount, 2);
                $nestedData[] = number_format($post->rcv_amount, 2);
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                    <button id="' . $post->client_id . '" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
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
        return $json_data;
    }

    public function todayCollectedBill($request)
    {
        $today = date("Y-m-d");
        // and receive_date='$today'
        $company_id=\Settings::company_id();
        $sql="
        
            SELECT rcv_amount, dis_amount, q.client_id, c.client_id username, c.client_name,c.cell_no, c.connection_mode, c.billing_responsible,p.package_name, p.package_price, IFNULL(c.permanent_discount,0) permanent_discount FROM (
            
            SELECT SUM(receive_amount) rcv_amount,SUM(discount_amount) dis_amount,client_id FROM bill_receives WHERE company_id =$company_id  GROUP BY client_id

            ) AS q 
            INNER JOIN clients c ON c.auth_id=q.client_id AND c.company_id=$company_id
            LEFT JOIN packages p ON p.id=c.package_id AND p.company_id=$company_id 
            where 1
        ";
        if (auth()->user()->can('isp-only-responsible-bill')) {
            $sql.=" and c.billing_responsible = ".auth()->user()->id;          
        }
       
        if (!empty($request->input('search.value'))) {
            $search = trim($request->input('search.value'));
            $sql.=" and (c.client_id like '%".$search."%' or c.client_name like '%".$search."%' or c.cell_no like '%".$search."%')"; 
        }
        $sql.=" ORDER BY c.client_id ASC";

        $dir = $request->input('order.0.dir');

        $posts=  DB::select( $sql);
        
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $key=>$post) {

                $clinet = Clients::where("auth_id", $post->client_id)->first();

                $nestedData = array();

                $nestedData[] = $key+1;
                $nestedData[] = $clinet->client_id . "<br>" . $clinet->client_name;
                $nestedData[] = $clinet->cell_no;
                $nestedData[] = $clinet->package->package_name . "/" . $clinet->package->package_price;
                $nestedData[] = number_format($post->dis_amount, 2);
                $nestedData[] = number_format($post->rcv_amount, 2);
                $nestedData[] = '<div class="btn-group align-top" role="group">
                                    <button id="' . $post->client_id . '" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
                                </div>';

                $data[] = $nestedData;
            }
        }

        return [
            "draw" => intval($request->input('draw')),
            "data" => $data
        ];
    }

    public function dueBill($request)
    {
        // $columns = array(
        //     0 => 'id',
        //     1 => 'client_id'
        // );

        // $totalData = Bills::query()
        //     ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as due_amount"))
        //     ->where("company_id", \Settings::company_id())
        //     ->where("bill_approve", 1);
        //     if (auth()->user()->can('isp-only-responsible-bill')) {
        //         $totalData = $totalData->whereHas('client', function ($query) {
        //             $query->where('billing_responsible', auth()->user()->id);
        //         });
        //     }
        //     $totalData = $totalData   ->where("client_type", $this->clientType)
        //     ->groupBy("client_id")
        //     ->havingRaw('due_amount > 0')
        //     ->count();

        // $totalFiltered = $totalData;

        // $limit = $request->input('length');
        // $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');

        // if (empty($request->input('search.value'))) {

        //     $posts = Bills::query()
        //         ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as due_amount,client_id"))
        //         ->where("company_id", \Settings::company_id())
        //         ->where("bill_approve", 1);
        //         if (auth()->user()->can('isp-only-responsible-bill')) {
        //             $posts = $posts->whereHas('client', function ($query) {
        //                 $query->where('billing_responsible', auth()->user()->id);
        //             });
        //         }
        //         $posts = $posts->where("client_type", $this->clientType)
        //         ->groupBy("client_id")
        //         ->orderBy("client_id", $dir)
        //         ->havingRaw('due_amount > 0')
        //         ->get();
        // } else {
        //     $search = $request->input('search.value');

        //     $clients = Clients::query()
        //         ->select("auth_id")
        //         ->where('client_id', '=', "$search")
        //         ->where("company_id", \Settings::company_id())
        //         ->orWhere('client_name', 'LIKE', "%{$search}%")
        //         ->where(["connection_mode" => 1])
        //         //->where("client_type", $this->clientType)
        //         ->orderBy($order, $dir)
        //         ->get();

        //     $posts = Bills::query()
        //         ->select(DB::raw("(sum(payable_amount) - sum(receive_amount)-sum(discount_amount)) as due_amount,client_id"))
        //         ->where("bill_approve", 1)
        //         ->where("client_type", $this->clientType);

        //         if (auth()->user()->can('isp-only-responsible-bill')) {
        //             $posts = $posts->whereHas('client', function ($query) {
        //                 $query->where('billing_responsible', auth()->user()->id);
        //             });
        //         }
        //     if (count($clients)) {
        //         $ids = [];
        //         foreach ($clients as $clientss) {
        //             $ids[] = $clientss->auth_id;
        //         }
        //         $posts = $posts->whereIn("client_id", $ids)->where("company_id", \Settings::company_id());
        //     }
        //     $posts = $posts->groupBy("client_id")
        //         ->orderBy("client_id", $dir)
        //         ->havingRaw('due_amount > 0')
        //         ->get();

        //     $totalFiltered = count($posts);
        // }
        // $data = array();
        // if (!empty($posts)) {
        //     $sl = count($posts);
        //     foreach ($posts as $key => $post) {
        //         $client = Clients::where("auth_id", $post->client_id)->first();


        //         $nestedData = array();

        //         if ($dir == "asc") {
        //             $nestedData[] = $key + 1;
        //         } else {
        //             $nestedData[] = $sl;
        //             $sl--;
        //         }
        //         $nestedData[] = $client->client_id . "<br>" . $client->client_name;
        //         $nestedData[] = $client->cell_no;
        //         $nestedData[] = $client->package->package_name . "<br><span>&#2547;.</span>" . number_format($client->package->package_price, 2);
        //         $nestedData[] = number_format($client->permanent_discount, 2);
        //         $payable = number_format($post->due_amount, 2);

        //         $nestedData[] = $payable > 0 ? "<b style='color:red;'>" . $payable . "</b>" : $payable;
        //         $action = '<div class="btn-group align-top" role="group">';
        //         if (auth()->user()->can('isp-bill-collect')) {//bill receive access
        //             $action .= '<button id="' . $post->client_id . '" class="open btn btn-primary btn-sm badge">
        //                             <i class="la la-money"></i></button>';
        //         }
        //         $action .= '<button id="' . $post->client_id . '" title="Send SMS" class="dueSMS btn btn-warning btn-sm badge">
        //                             <i class="la la-envelope"></i></button>
        //                             <button id="' . $post->client_id . '" title="Commitment Date" class="commitDate btn btn-success btn-sm badge">
        //                             <i class="la la-calendar"></i></button>
        //                             <button id="' . $post->client_id . '" class="viewBill btn btn-info btn-sm badge">
        //                             <i class="la la-eye"></i></button>
        //                         </div>';
        //         $nestedData[] = $action;
        //         $data[] = $nestedData;


        //     }
        // }

        // return $json_data = array(
        //     "draw" => intval($request->input('draw')),
        //     "recordsTotal" => intval($totalData),
        //     "recordsFiltered" => intval($totalFiltered),
        //     "data" => $data
        // );









        $limit = $request->input('length');
        $start = $request->input('start');
        $company_id=\Settings::company_id();
        $totalData=Clients::company()->count();
        $sql="
        
            SELECT debit, credit,sum(debit)-sum(credit) due, q.client_id, c.client_id username, c.client_name,c.cell_no, c.connection_mode, c.billing_responsible,p.package_name, p.package_price, IFNULL(c.permanent_discount,0) permanent_discount,c.termination_date FROM (

            SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE company_id =$company_id  GROUP BY client_id
            
            UNION ALL 
            
            SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit,client_id FROM bill_receives WHERE company_id =$company_id  GROUP BY client_id

            ) AS q 
            INNER JOIN clients c ON c.auth_id=q.client_id AND c.company_id=$company_id
            LEFT JOIN packages p ON p.id=c.package_id AND p.company_id=$company_id 
            where 1
        ";
        if (auth()->user()->can('isp-only-responsible-bill')) { 
            $sql.=" and c.billing_responsible = ".auth()->user()->id;          
        }
        if ($request->date_filter) {
            $sql.=" and c.termination_date Between '".$request->date_from."' and '".$request->date_to."'";          
        }
        if (!empty($request->input('search.value'))) {
            $search = trim($request->input('search.value'));
            $sql.=" and (c.client_id like '%".$search."%' or c.client_name like '%".$search."%' or c.cell_no like '%".$search."%')"; 
        }
        $sql.=" group by q.client_id having due>0 ORDER BY c.client_id ASC ";
        $posts=  DB::select( $sql);
        
        $data = array();
        if (!empty($posts)) {
            $sl = count($posts);
            foreach ($posts as $key => $post) {               

                    $post->due_amount=$post->debit-$post->credit;

                    $nestedData = array();
                   
                        $nestedData[] = $key + 1;
                  
                    $clientStatus = "#2ebd2e";
                    $clientStatuTitles = "Active";
                    if ($post->connection_mode == 0) {
                        $clientStatus = "red";
                        $clientStatuTitles = "Inactive";
                    } elseif ($post->connection_mode == 2) {
                        $clientStatus = "yellow";
                        $clientStatuTitles = "Locked";
                    }
                    $nestedData[] = "<font class='pull-left'>" . $post->client_id . "<br>" . $post->client_name . "<br>" . $post->cell_no . "</font><b title='$clientStatuTitles' style='width:10px;border-radius:50%;height: 10px; background: $clientStatus' class='pull-right'>&nbsp;</b>";
                    $nestedData[] = $post->cell_no;
                    $nestedData[] = $post->termination_date;
                    //$nestedData[] = $clinet->termination_date;
                    $nestedData[] = $post->package_name . "<br><span class='taka'>&#2547;.</span>" . $post->package_price;
                    $nestedData[] = number_format($post->permanent_discount, 2);
                    $payable = number_format($post->due_amount, 2);
                    $nestedData[] = $payable > 0 ? "<b style='color:red;'>" . $payable . "</b>" : $payable;
                    // $nestedData[] = number_format($post->rcv_amount, 2);
                    $action = '<div class="btn-group align-top" role="group">';
                    if (auth()->user()->can('isp-bill-collect')) { //bill receive access
                        $action .= '<button  id="' . $post->client_id . '" title="Collect bill" class="open btn btn-primary btn-sm badge">
                                                    <i class="la la-money"></i></button>';
                    }

                    $action .= ' <button ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Send SMS" class="dueSMS btn btn-warning btn-sm badge">
                                    <i class="la la-envelope"></i></button>
                                    <button ' . ($post->due_amount > 0 ? '' : 'disabled') . ' id="' . $post->client_id . '" title="Commitment Date" class="commitDate btn btn-success btn-sm badge">
                                    <i class="la la-calendar"></i></button>';
                    
                    if (auth()->user()->can('isp-bill-collect')) { //bill receive access
                    $action .= '<button id="' . $post->client_id . '" title="Create Other Bill" class="otherBill btn btn-secondary btn-sm badge">
                                    <i class="la la-credit-card"></i></button>';
                    }
                    $action .= '<button id="' . $post->client_id . '" title="View" class="viewBill btn btn-info btn-sm badge">
                                    <i class="la la-eye"></i></button>
                                </div>';
                    $nestedData[] = $action;
                    $data[] = $nestedData;

            }
        }
       

       
        return [
            "data" => $data,
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval(count($posts)),
       ];
    }

    public function generateReactiveClientBill($request)
    {
        $payable = $request->payable;
        $total_amount_origin = $request->receive_amount + $request->discount_amount;//with discount
        $total_amount = $request->receive_amount + $request->discount_amount;//with discount
      

        $client = Clients::find($request->id2);
        $packages = Packages::find($request->package_id);

        //generate current bill
        $dateString = date('ym'); //Generate a datestring.

        $today = date("Y-m-d");
        $this_month = date("m");
        $this_year = date("Y");
        $this_month_text = date("M");
        $data = $sms_data = $historyData = array();

        $bill_type = 1;//package bill/monthly bill

        $bill_exist = Bills::query()
            ->where('bill_type', $bill_type)
            ->where('bill_month', $this_month)
            ->where('bill_year', $this_year)
            ->where('client_id', $client->auth_id)
            ->count();
        $bill_count = $bill_exist + 1;

        $bill_id = $dateString . $bill_type . $bill_count . $client->id;


        $client->permanent_discount = $request->permanent_discount;

        $new_data = array();
        $new_data["company_id"] = $client->company_id;
        $new_data["client_id"] = $client->auth_id;
        $new_data["client_initial_id"] = $client->client_id;
        $new_data["bill_id"] = $bill_id;
        $new_data["bill_date"] = $today;
        $new_data["bill_month"] = $this_month;
        $new_data["bill_year"] = $this_year;
        $new_data["bill_type"] = $bill_type;
        $new_data["package_id"] = $packages->id;
        $new_data["payable_amount"] = $request->payable;
        $new_data["permanent_discount_amount"] = $client->permanent_discount;
        $new_data["created_at"] = $new_data["bill_date"];
      
        $data[] = $new_data;

        $history = array();
        $history["particular"] = "Monthly bill";
        $history["company_id"] = $client->company_id;
        $history["client_id"] = $new_data["client_id"];
        $history["bill_id"] = $new_data["bill_id"];
        $history["bill_year"] = $new_data["bill_year"];
        $history["bill_month"] = $new_data["bill_month"];
        $history["bill_amount"] = $new_data["payable_amount"];
        $history["receive_amount"] = 0;
        $history["created_at"] = date("Y-m-d H:i");
        $history["updated_at"] = date("Y-m-d H:i");
        $historyData[] = $history;




        //check package change
        // if ($client->package_id != $request->package_id) {
        //generate ticket
        //    $ticket = new TicketService();
        // $ticket->generateTicketPackageChange($client->auth_id, $request->package_id, $client->package_id);
        // }

        // $dueBill = Bills::query()->select(DB::raw('(SUM(payable_amount)-SUM(receive_amount)) AS payable'))
        //     ->where("client_id", $client->auth_id)->get();
        // if (count($dueBill) > 0) {
        //     $client_due = $dueBill[0]->payable;
        // } else {
        //     $client_due = 0;
        // }
        // $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
        // $name = explode(" ", $client->client_name)[0];
        // $cell_no = $client->cell_no;
        // if ($client_due > 0) {
        //     $com_sms = '';
        //     if ($request->commitment_date) {
        //         $commitment_date = date("d/m/Y", strtotime($request->commitment_date));
        //         $com_sms = ", committed date is " . $commitment_date;
        //     }
        //     $sms_text = "Dear $name, successfully active your internet  " . $packages->package_price . "tk/monthly. Due bill is " . ($client_due) . "tk" . $com_sms . ". Pay Your Due Bill as Soon as possible in expired deadline.";
        // } else {
        //     $sms_text = "Dear $name, successfully active your internet  " . $packages->package_price . "tk/monthly.";
        // }
        // $sms_count = round(strlen($sms_text) / 160) == 0 ? 1 : round(strlen($sms_text) / 160);
        // $sms_type = "english";
        // $sms_status = "Pending";
        // $is_retry = 1;

        // $created_at = date("Y-m-d H:i");
        // $sms_data[] = [
        //     "company_id" => $client->company_id,
        //     "sms_receiver" => $cell_no,
        //     "sms_sender" => "Administrator",
        //     "sms_count" => $sms_count,
        //     "sms_type" => $sms_type,
        //     "sms_text" => $sms_text,
        //     "sms_api" => $sms_api,
        //     "is_retry" => $is_retry,
        //     "sms_status" => $sms_status,
        //     "sms_schedule_time" => $created_at,
        //     "sent_time" => $created_at,
        //     "created_at" => $created_at
        // ];

        $result = false;
        if ($data) {
            $query = Bills::query()->insert($data);
            if ($query) {
                $newBill = new BillReceives();
                $newBill->bill_approve = 1;
                $newBill->company_id = $new_data["company_id"];
                $newBill->payment_method_id = $request->payment_method_id;
                $newBill->bill_id = uniqid();
                $newBill->client_id = $new_data["client_id"];
                $newBill->client_type = 1;//isp_client
                $newBill->particular = 'Collection';
                $newBill->receive_amount = $request->receive_amount;
                $newBill->discount_amount = $request->discount_amount;
                $newBill->note = $request->note;
                $newBill->bill_date = date("Y-m-d");
                $newBill->receive_date = date("Y-m-d");
                $newBill->bill_month = date("m");
                $newBill->bill_year = date("Y");
                $newBill->receive_by = $request->collected_by;
                $result= $newBill->save();
            }
        }
        if ($result) {
            $query = Clients::whereId($client->id)
                ->update(["permanent_discount" => $client->permanent_discount, "package_id" => $request->package_id, "connection_mode" => 1]);
            if ($query) {
                if ($request->send_sms == 1) {
                    //SmsHistory::query()->insert($sms_data);
                }
            }
        }


        return $result;
    }

    public function getBillGenerateReport($request)
    {
        if ($request) {
            $zone_id = $request->zone_id;
            return Bills::whereHas('client', function ($q) use ($zone_id) {

                if ($zone_id > 0) {
                    $q->where("zone_id", '=', $zone_id);
                }

            })
                ->select(DB::raw("(SUM(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as payable,bill_date,client_id"))
                ->where(["bill_month" => $request->month, "bill_year" => $request->year, 'receive_amount' => 0])

                ->groupBy("client_id")
                //->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)))"),">",0)
                ->get();
        } else {
            return Bills::query()
                ->select(DB::raw("(SUM(payable_amount)-(sum(receive_amount)+sum(discount_amount))) as payable,bill_date,client_id"))
                ->where(["bill_month" => date('m'), "bill_year" => date("Y"), 'receive_amount' => 0])
                ->groupBy("client_id")
                //->having(DB::raw("(sum(payable_amount)-(sum(receive_amount)+sum(discount_amount)))"),">",0)
                ->get();
        }

    }
    public static function maxNote($id, $m, $y)
    {
        return Bills::query()
            ->select(DB::raw("note, max(receive_date)"))
            ->where(["bill_month" => $m, "bill_year" => $y, 'client_id' => $id])
            ->where('receive_amount', '>', 0)
            //->having(DB::raw("receive_amount"),">",0)
            ->first();
    }

    public function getIspClients($request)
    {
        if ($request) {
            $zone_id = $request->zone_id;
            $clients = Clients::where("company_id", \Settings::company_id());
            if ($request->status) {
                $status = '';
                if ($request->status == 1) {
                    $status = 1;
                }
                if ($request->status == 2) {
                    $status = 0;
                }
                if ($request->status == 3) {
                    $status = 2;
                }
                $clients = $clients->where("connection_mode", $status);
            }
            if ($zone_id) {
                $clients = $clients->where("zone_id", $zone_id);
            }
            $clients = $clients->orderBy('join_date', 'asc')->get();

            return $clients;
        }
    }

    public function getApprovalPending($type = "isp")
    {
        $totalData = Bills::query()
            ->where("company_id", \Settings::company_id())
            ->where("bill_approve", 2)
            ->count();

        return $totalData;
    }


    public function isp_client_current_due($client_id){
        $company_id=\Settings::company_id();
        $bill = DB::select("SELECT SUM(debit)-SUM(credit) due FROM (

        SELECT SUM(payable_amount) debit, 0 credit,client_id FROM bills WHERE client_id=$client_id and company_id=$company_id GROUP BY client_id
        
        UNION ALL 
        
        SELECT 0 debit , SUM(receive_amount)+SUM(discount_amount) credit,client_id FROM bill_receives WHERE client_id=$client_id and company_id=$company_id GROUP BY client_id

        ) AS q GROUP BY client_id ");
        if($bill){
            return $bill[0]->due;
        }else{
            return 0;
        }
    
    }
}