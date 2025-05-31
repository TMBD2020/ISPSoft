<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;

class LoanController extends Controller
{

    protected $loans = "loans";
    protected $loan_persons = "loan_persons";

    public  function __construct(){
        $this->middleware("auth");
    }
    public function index()
    {
        $loan_persons = DB::table($this->loan_persons)->get();
        return view("back.liability.liability_", compact("loan_persons"));
    }

    public function save_loan(Request $request)
    {
        $data = array();
        if($request->loan_type==1){
            $data["loan_to"]       = $request->loan_to;
            $data["receive_from"]  = $request->loan_person;
            $data["person_id"]  = $request->loan_person;
            $data["receive_amount"]   = $request->loan_amount;
            $data["payment_amount_per_month"]   = $request->payment_amount_per_month;
            $data["payment_date_per_month"]   = date("Y-m-d", strtotime($request->payment_date_per_month));
            $data["payment_amount"]   = 0;
        }else{
            $data["loan_to"]       = $request->loan_person;
            $data["receive_from"]  = $request->loan_to;
            $data["person_id"]  = $request->loan_person;
            $data["payment_amount"]   = $request->loan_amount;
            $data["receive_amount"]   = 0;
        }

        $data["receive_date"]  = date("Y-m-d", strtotime($request->receive_date));
        $data["note"]          = $request->note;
        $data["loan_type"]     = $request->loan_type;
        $data["created_at"]    = date("Y-m-d H:i:s");
        $data["updated_at"]    = date("Y-m-d H:i:s");

        $result = DB::table($this->loans)->insert($data);

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function SaveLoanPerson(Request $request)
    {
        if($request->action==1){
            $result = DB::table($this->loan_persons)->insert(
                [
                    "name"          => $request->name,
                    "mobile"        => $request->mobile,
                    "address"       => $request->address,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ]
            );
        }else{
            $data =array(
                "name"          => $request->name,
                "mobile"        => $request->mobile,
                "address"       => $request->address,
                "updated_at"    => date("Y-m-d H:i:s")
            );
            $result = DB::table($this->loan_persons)->whereId($request->creditor_id)->update($data);
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function LoanReceiveList(Request $request)
    {
        $columns = array(
            0 => $this->loans.'.id',
            1 => 'receive_date',
            2 => 'receive_date',
            3 => 'receive_from',
            4 => 'loan_to',
            5 => 'receive_amount',
            6 => 'note',
        );

        $totalData = DB::table($this->loans)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->loans)
                ->select("*", $this->loans.".id as loanId")
                ->leftJoin($this->loan_persons, $this->loans.".person_id","=",$this->loan_persons.".id")
                ->where('loan_type',1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->loans)
                ->select("*", $this->loans.".id as loanId")
                ->leftJoin($this->loan_persons, $this->loans.".person_id","=",$this->loan_persons.".id")
                //->where('id','LIKE',"%{$search}%")
                ->where('loan_type',1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->loans)
               // ->where('id','LIKE',"%{$search}%")
                ->where('loan_type',1)
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->loanId;
                $nestedData[] = $post->loanId;
                $nestedData[] = date("d/m/Y", strtotime($post->receive_date));
                $nestedData[] = $post->name;
                $nestedData[] = $post->loan_to;
                $nestedData[] = $post->receive_amount;
                $nestedData[] = $post->note;
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

    public function LoanSummerList(Request $request)
    {
        $columns = array(
            0 => 'person_id',
            1 => 'person_id',
           2 => 'receive',
            3=> 'payment',
            4 => 'summery'
        );

        $totalData = DB::table($this->loans)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $person_list = DB::table($this->loan_persons)->get();

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->loans)
                ->select( DB::raw("sum(payment_amount) as payment"), DB::raw("sum(receive_amount) as receive"), DB::raw("sum(receive_amount)-sum(payment_amount) as summery"), "person_id")
                ->groupBy(["person_id"])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->person_id;
                $nestedData[] = $post->person_id;
                foreach ($person_list as $item) {
                    if($item->id==$post->person_id){
                        $nestedData[] = $item->name;
                    }
                }
                $nestedData[] = $post->receive;
                $nestedData[] = $post->payment;
                $nestedData[] = $post->summery;
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

    public function LoanPaymentList(Request $request)
    {
        $columns = array(
            0 => $this->loans.'.id',
            1 => 'receive_date',
            2 => 'receive_date',
            3 => 'person_id',
            4 => 'receive_from',
            5 => 'payment_amount',
            6 => 'note',
        );

        $totalData = DB::table($this->loans)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->loans)
                ->select("*", $this->loans.".id as loanId")
                ->leftJoin($this->loan_persons, $this->loans.".person_id","=",$this->loan_persons.".id")
                ->where('loan_type',2)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->loans)
                ->select("*", $this->loans.".id as loanId")
                ->leftJoin($this->loan_persons, $this->loans.".person_id","=",$this->loan_persons.".id")
                ->where('id','LIKE',"%{$search}%")
                ->where('loan_type',2)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->loans)
                ->where('id','LIKE',"%{$search}%")
                ->where('loan_type',2)
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->loanId;
                $nestedData[] = $post->loanId;
                $nestedData[] = date("d/m/Y", strtotime($post->receive_date));
                $nestedData[] = $post->name;
                $nestedData[] = $post->receive_from;
                $nestedData[] = $post->payment_amount;
                $nestedData[] = $post->note;
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

    public function CreditorList(Request $request)
    {
        $columns = array(
            0 => $this->loan_persons.'.id',
            1 => 'name',
            2 => 'name',
            3 => 'mobile',
            4 => 'address',
        );

        $totalData = DB::table($this->loan_persons)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = DB::table($this->loan_persons)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =   DB::table($this->loan_persons)
                ->where('id','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered =  DB::table($this->loan_persons)
                ->where('id','LIKE',"%{$search}%")
                ->count();
        }

        $data = array();

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();

                $nestedData[] = $post->id;
                $nestedData[] = $post->id;
                $nestedData[] = $post->name;
                $nestedData[] = $post->mobile;
                $nestedData[] = $post->address;
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


    public function LoanPersonList(Request $request)
    {
        $result =  DB::table($this->loan_persons)->get();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }

    public function PayableLiability(Request $request)
    {
        $result = DB::table($this->loans)
            ->select(DB::raw("sum(receive_amount)-sum(payment_amount) as payable"))
            ->where('person_id',$request->id)
            ->first();
        if($result){
            echo $result->payable;
        }else{
            echo 0;
        }
    }

    public function CreditorUpdate(Request $request)
    {
        $result = DB::table($this->loan_persons)->whereId($request->id)->first();
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function creditor_liability_list(Request $request)
    {
        $from_date = date("Y-m-d", strtotime($request->from_date));
        $to_date = date("Y-m-d", strtotime($request->to_date));
        $result = DB::table($this->loans)
		->select(DB::raw("sum(receive_amount) as rcv_amount, sum(payment_amount) as pay_amount,receive_date"))
            ->where("person_id",$request->id)
            ->whereBetween('receive_date', array($from_date, $to_date))
			->groupBy('receive_date')
            ->get();
        if(count($result)>0){
            echo json_encode($result);
        }else{
            echo 0;
        }
    }
    public function downloadPDF(Request $request) {

        $operation = $request->operation;

        $person = DB::table($this->loan_persons)->whereId($request->creditor_id)->first();

        $from_date = date("Y-m-d", strtotime($request->from_date));
        $to_date = date("Y-m-d", strtotime($request->to_date));
        $result = DB::table($this->loans)
            ->select(DB::raw("sum(receive_amount) as rcv_amount, sum(payment_amount) as pay_amount,receive_date"))
            ->where("person_id",$request->creditor_id)
            ->whereBetween('receive_date', array($from_date, $to_date))
            ->groupBy('receive_date')
            ->get();
        if($operation!="Print"){
            $pdf = PDF::loadView('back.liability.liability_pdf', compact('result','person','from_date','to_date','operation'));
            return $pdf->download('liability.pdf');
        }else{
            return view('back.liability.liability_pdf',compact('result','person','from_date','to_date','operation'));
        }



    }
    public function loanDelete(Request $request)
    {
        $result = DB::table($this->loans)->whereId($request->id)->delete();
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

}
