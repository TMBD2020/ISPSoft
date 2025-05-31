<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Carbon\Carbon;
	use DB;
	use App\Model\IspBillHistorys;
	use App\Model\IspResellers;
	use App\Model\IspResellerBills;
	use App\Model\Packages;
	
	class AutoBillController extends Controller
	{
		protected $bills = "bills";
		protected $clients = "clients";
		protected $packages = "packages";
		protected $package_changes = "package_changes";
		protected $sms_history = "sms_history";
		 public  function __construct(){
        $this->middleware("auth");
    }
		public  function index(){
			//error_reporting(1);
			$dateString = date('Ymd'); //Generate a datestring.
			$receiptNumber = 1;  //You will query the last receipt in your database
			//and get the last $receiptNumber for that branch and add 1 to it.;
			
			
			
			$start = Carbon::now()->startOfMonth()->toDateString()." 00:00:00";
			$end = Carbon::now()->endOfMonth()->toDateString()." 23:00:00";
			
			$clients = DB::table($this->clients)
            ->select($this->clients.".id as cl_id","clients.*","packages.*")
            ->leftJoin($this->packages,$this->packages.".id","=",$this->clients.".package_id")
            ->where($this->clients.".company_id",auth()->user()->company_id)
            ->where("connection_mode",1)
            //->where("clients.id",10)
            ->get();
			
		
			  $today      = date("Y-m-d");
				$this_month = date("m");
				$this_month_text = date("M");
			$this_year  = date("Y"); 
			/* 
			$today      = "2021-09-29";
			$this_month = "9";
			$this_year  = "2021"; */
			$all = [];
			$data = $sms_data = $historyData = array();
			foreach ($clients as $client) {
				$bill_type = 1;//package bill/monthly bill
				
				$bill_exist = DB::table($this->bills)
				->where("company_id",auth()->user()->comapany_id)
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('client_id', $client->cl_id)
                ->count();
				$bill_count = $bill_exist+1;
				if( $bill_exist==0){
				$all[]=$client->cl_id;}
				
				//$bill_id = $this_year.$this_month.$bill_type.$bill_count.$client->cl_id;
				
				if($receiptNumber < 9999) {
					
					$receiptNumber = $receiptNumber + 1;
					
					}else{
					$receiptNumber = 1;
				}
				$branchNumber = $client->id; //Get the branch number somehow.
				$bill_id=$dateString.$branchNumber.$receiptNumber;
				
				if($client->package_change==1){
					$package_change_date =  date("Y-m",strtotime($client->package_change_date));
					$package_change_day =  date("d",strtotime($client->package_change_date));
					if($package_change_date == $this_year."-".$this_month){
						$change_package_data = DB::table($this->package_changes)->where(
                        [
						"ref_client_id" =>  $client->cl_id,
						"change_date"   =>  $client->package_change_date
                        ]
						)->first();
						
						if($change_package_data){
							$start_of_month     = Carbon::create($client->package_change_date)->startOfMonth()->toDateString();
							$end_of_month       = Carbon::create($client->package_change_date)->endOfMonth()->toDateString();
							$new_package_data   = DB::table($this->packages)->whereId($change_package_data->new_package_id)->first();
							$old_package_data   = DB::table($this->packages)->whereId($change_package_data->old_package_id)->first();
							
							if($start_of_month==$client->package_change_date){
								$client->package_price = $new_package_data->package_price;
								}else{
								$end_day = date("d", strtotime($end_of_month));
								$total_new_package_day = $end_day-$package_change_day;
								$total_old_package_day = $package_change_day-1;
								
								//old package bill
								$day_wise_old_bill = round($old_package_data->package_price/30);//day wise calculate
								$old_bill = round($total_old_package_day*$day_wise_old_bill);
								
								//new package bill
								$day_wise_bill = round($new_package_data->package_price/30);//day wise calculate
								$client->package_price = round($total_new_package_day*$day_wise_bill)+$old_bill;//total bill
								
							}
							DB::table($this->clients)->whereId($client->cl_id)->update(["package_change"=>0]);
						}
					}
				}
				
				if($bill_exist==0){
					$new_data=array();
					$new_data["company_id"]                  = auth()->user()->company_id;
					$new_data["client_id"]                  = $client->cl_id;
					$new_data["client_initial_id"]          = $client->client_id;
					$new_data["bill_id"]                    = $bill_id;
					$new_data["bill_date"]                  = $today;
					$new_data["bill_month"]                 = $this_month;
					$new_data["bill_year"]                  = $this_year;
					$new_data["bill_type"]                  = $bill_type;
					$new_data["bill_status"]                = 0;
					$new_data["package_id"]                 = $client->package_id;
					$new_data["payable_amount"]             = $client->package_price - $client->permanent_discount;
					$new_data["permanent_discount_amount"]  = $client->permanent_discount;
					$data[]= $new_data;
					
					$history=array();
					$history["particular"]= "Monthly bill";
					$history["company_id"]                  = auth()->user()->company_id;
					$history["client_id"]= $new_data["client_id"];
					$history["bill_id"]= $new_data["bill_id"];
					$history["bill_year"]= $new_data["bill_year"];
					$history["bill_month"]= $new_data["bill_month"];
					$history["bill_amount"]= $new_data["payable_amount"];
					$history["receive_amount"]= 0;
					$history["created_at"]= date("Y-m-d H:i");
					$history["updated_at"]= date("Y-m-d H:i");
					$historyData[]=$history;
					
					
					if($client->payment_alert_sms==1){
					$dueBill = DB::table($this->bills)->select(DB::raw('(SUM(payable_amount)-SUM(receive_amount)) AS payable'))
					->where("client_id",$client->cl_id)->get();
						if(count($dueBill)>0){
							$client_due=$dueBill[0]->payable;
						}
						else{
							$client_due=0;
						}
					
						$sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
						$name = explode(" ",$client->client_name)[0];
					 	$sms_text = "Dear ".$name ." (".$client->client_id."), the bill for the month of ".$this_month_text." has been generated as ".$new_data["payable_amount"]."tk. Unless payment is not confirmed the link will automatically expire on ".$this->ordinal_suffix($client->payment_dateline)." ".$this_month_text.". Total due ".($new_data["payable_amount"]+$client_due)."tk. Pay your bill today (Bkash/Cash: 01993678660) Call: 01841918091";
					 	//$sms_text = "Dear ".$client->client_name." (".$client->client_id."),Internet bill for month of ".$this_month_text." has created ".$new_data["payable_amount"]."tk. Link will Expire on ".$this->ordinal_suffix($client->payment_dateline)." ".$this_month_text.".Don't ignore it to avoid interrupt. Total due is : ".($new_data["payable_amount"]+$client_due)."tk আজই আপনার বিল পরিশোধ  করুন(Bkash/Nagad:01993678660) Call:01841918091";
						//echo $sms_text = "Dear ".$client->client_name." (".$client->client_id."), your last month bill ".$new_data["payable_amount"]."tk. has been due.".$client_due;
						$cell_no        = $client->cell_no;
						$sms_count        = round(strlen($sms_text)/160)==0?1:round(strlen($sms_text)/160);
						$sms_type="english";
						if(strlen($cell_no)==13){
							$sms_status="Pending";
							$is_retry=1;
						}else{
							$sms_status="Receiver Error";
							$is_retry=0;
						}
						$created_at = date("Y-m-d H:i");
						
						$sms_data[] = [
                        "company_id"=>auth()->user()->company_id,
                        "sms_receiver"=>$cell_no,
                        "sms_sender"=>"Administrator",
                        "sms_count"=>$sms_count,
                        "sms_type"=>$sms_type,
                        "sms_text"=>$sms_text,
                        "sms_api"=>$sms_api,
                        "is_retry"=>$is_retry,
                        "sms_status"=>$sms_status,
                        "sms_schedule_time"=>$created_at,
                        "sent_time"=>$created_at,
                        "created_at"=>$created_at
						];
						
					}
					
					//print_r ($sms_data);
				}
				
				
			}
			//	print_r($data);
			if($data){
				
				
				$query = DB::table($this->bills)->insert($data);
				
				if($query){
					
					if($historyData){
                        IspBillHistorys::query()->insert($historyData);
					}
					  DB::table($this->sms_history)->insert($sms_data);
					echo 1;
					}else{
					echo 0;
				}
				} else {
				echo "created all bill";
			}
			
		}
		
		
		
		    public  function reseller_bill(){
        $dateString = date('Ymd'); //Generate a datestring.
        $branchNumber = auth()->user()->company_id; //Get the branch number somehow.
        $receiptNumber = 1;  //You will query the last receipt in your database
//and get the last $receiptNumber for that branch and add 1 to it.;



        $start = Carbon::now()->startOfMonth()->toDateString()." 00:00:00";
        $end = Carbon::now()->endOfMonth()->toDateString()." 23:00:00";

        $clients = IspResellers::query()->where(["reseller_activity"=>1,"company_id"=>auth()->user()->company_id])->get();

//        $today      = date("Y-m-d");
//        $this_month = date("m");
//        $this_year  = date("Y");

        $today      = "2021-09-29";
        $this_month = "09";
        $this_year  = "2021";
        $data = $sms_data = $historyData = array();
        foreach ($clients as $client) {
            $bill_type = 1;//package bill/monthly bill

            $bill_exist = IspResellerBills::query()
                ->where("company_id",auth()->user()->company_id)
                ->where('bill_type', $bill_type)
                ->where('bill_month', $this_month)
                ->where('bill_year', $this_year)
                ->where('client_id', $client->id)
                ->count();

            if($receiptNumber < 9999) {

                $receiptNumber = $receiptNumber + 1;

            }else{
                $receiptNumber = 1;
            }
            $bill_id=$dateString.$branchNumber.$receiptNumber;

            if($bill_exist==0){
                $new_data=array();
                $new_data["company_id"]                  = auth()->user()->company_id;
                $new_data["client_id"]                  = $client->id;
                $new_data["client_initial_id"]          = $client->reseller_id;
                $new_data["bill_id"]                    = $bill_id;
                $new_data["bill_date"]                  = $today;
                $new_data["bill_month"]                 = $this_month;
                $new_data["bill_year"]                  = $this_year;
                $new_data["bill_type"]                  = $bill_type;
                $new_data["created_at"]                  = date("Y-m-d H:i:s");
                $new_data["bill_status"]                = 0;
                if($client->reseller_type=="Mac"){

                    $package = Packages::find($client->package_id);


                    $new_data["payable_amount"]             = $package->package_price ;
                }else{
                    $bandwidth = json_decode($client->bandwidth_details);

                    $total=0;
                    foreach ($bandwidth as $row) {
                        $total+=$row->price;
                    }
                    $new_data["payable_amount"]             = $total ;
                }
                $data[]= $new_data;

                $history=array();
                $history["particular"]= "Monthly bill";
                $history["company_id"]                  = auth()->user()->company_id;
                $history["client_id"]= $new_data["client_id"];
                $history["bill_id"]= $new_data["bill_id"];
                $history["bill_year"]= $new_data["bill_year"];
                $history["bill_month"]= $new_data["bill_month"];
                $history["bill_amount"]= $new_data["payable_amount"];
                $history["receive_amount"]= 0;
                $history["bill_type"]   = 'reseller';
                $history["created_at"]= date("Y-m-d H:i");
                $history["updated_at"]= date("Y-m-d H:i");
                $historyData[]=$history;

//                exit();

//                    $sms_api = app('App\Http\Controllers\SMS\SMSAPIController')->defaultApi();
//                    $sms_text = "Dear ".$client->reseller_name." (".$client->client_id."), your last month bill ".$new_data["payable_amount"]."tk. has been due.";
//                    $cell_no        = $client->personal_contact;
//                    $sms_count        = round(strlen($sms_text)/160)==0?1:round(strlen($sms_text)/160);
//                    $sms_type="english";
//                    $sms_status="Pending";
//                    $created_at = date("Y-m-d H:i");
//                    $sms_data[] = [
//                        "sms_receiver"=>$cell_no,
//                        "sms_sender"=>"Administrator",
//                        "sms_count"=>$sms_count,
//                        "sms_type"=>$sms_type,
//                        "sms_text"=>$sms_text,
//                        "sms_api"=>$sms_api,
//                        "sms_status"=>$sms_status,
//                        "sms_schedule_time"=>$created_at,
//                        "sent_time"=>$created_at,
//                        "created_at"=>$created_at
//                    ];

            }
        }
        if($data){

            IF($historyData){
                IspBillHistorys::query()->insert($historyData);
            }
             $query = IspResellerBills::query()->insert($data);
            if($query){
              //  DB::table($this->sms_history)->insert($sms_data);
                 echo 1;
            }else{
                 echo 0;
            }
        } else {
            echo "created all bill";
        }

    }

		
		
		function ordinal_suffix($number){
			$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($number %100) >= 11 && ($number%100) <= 13){$abbreviation = $number. 'th';}
else{   $abbreviation = $number. $ends[$number % 10];}
   return $abbreviation;
			}
	}
