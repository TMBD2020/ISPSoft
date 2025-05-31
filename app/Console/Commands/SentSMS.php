<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\TmbdUsers;
use Illuminate\Support\Facades\Log;
class SentSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sent:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sent sms scheduler in one minute.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$now = date("Y-m-d H:i:s");
        //DB::table("test")->insert(["times"=>$now]);
        $user = TmbdUsers::find(1);            
        $apiData = DB::table("sms_api")->find($user->sms_api_id);
        if($apiData->id==3){
            $post_response = $this->muthoFun('+8801763329134','message',$apiData->api_url,$apiData->api_token);
        }else{
            $api_url = $apiData->api_url;
            $api_url = str_replace(["{{to}}","{{msg}}"], ['+8801763329134', 'message'],$api_url);
            $post_response = $this->smsAPI($api_url);
        }
       // $user->address=$post_response;
      //  $user->save();
        //exit();
        
        $smsList = DB::table("sms_history")->where(["is_retry"=> 1,'sms_status'=>'Pending'])->where("sms_schedule_time","<=",now())->limit( 100)->get();

        if (count($smsList) > 0) {
           
            foreach ($smsList as $row) {

                $id = $row->id;
                $sms_count = $row->sms_count;

                $user = TmbdUsers::find($row->company_id);
            
                $apiData = DB::table("sms_api")->where("id",$user->sms_api_id)->first();
                
                if($apiData){
                    $api_url = $apiData->api_url;
                    $sms_sender = $apiData->api_sender;
                    $api_token = $apiData->api_token;

                    if ($user) {                                       
    
                        $sms_balance = $user ? $user->sms_balance : 0;
                        $sms_rate = 0;
                        if ($user->sms_sender_type == 1) {
                            $sms_rate = $user->masking_rate;
                        } else {
                            $sms_rate = $user->non_masking_rate;
                        }
                        $sms_cost=$sms_rate*$sms_count;
                        if ($sms_balance > 0 && $sms_cost > 0) {
                            if ($sms_balance >= $sms_cost) {
    
                                $to = trim($row->sms_receiver);
                                $message = trim($row->sms_text);
    
                                if (strlen($to) == 11) {
                                    $to = "88" . $to;
    
                                    if (strlen($to) == 13) {
                                        $sms_receiver = $to;
                                        $msg = $message;
    
                                        $post_string = "api_token=$api_token&senderid=$sms_sender&contact_number=$sms_receiver&message=$msg";    
                                  
                                        $request = curl_init($api_url);
                                        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
                                        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
                                        $post_response = curl_exec($request);
                                        curl_close($request);  

                                        //$post_response=$post_string;
                                       //$array =  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $post_response), true );
                                        
                                       //if($array){
                                            //status of the request
                                        //    echo $array['status'] ;
                                    
                                            //status message of the request
                                        //    echo $array['message'] ;
                                        //}
                                        $sent_time = date("Y-m-d H:i:s");
    
                                        $user->sms_balance = $user->sms_balance - $sms_cost;
                                        //\Log::info( $bal->sms_balance);
                                        $user->last_sms_sent = $sent_time;
                                        $user->save();
    
    
                                        DB::table("sms_history")->where(["id" => $id])
                                            ->update(
                                                [
                                                    "is_retry" => 0,
                                                    "sms_status" => "Sent",
                                                    "sent_time" => $sent_time,
                                                  "response" => $post_response,
                                                   // "sms_count"	=> $array? $array['SmsCount']:0
                                                ]
                                            );
                                    }
    
                                } else {
                                    DB::table("sms_history")->where(["id" => $id])
                                        ->update(
                                            [
                                                "is_retry" => 0,
                                                "sms_status" => 'Receiver Error'
                                            ]
                                        );
                                }
                            } else {    
                                DB::table("sms_history")->where(["id" => $id])
                                    ->update(
                                        [
                                            "is_retry" => 0,
                                            "sms_status" => 'Receiver Error'
                                        ]
                                    );
                            }       
    
                        } else {    
                            DB::table("sms_history")->where(["id" => $id])
                                ->update(
                                    [
                                        "is_retry" => 0,
                                        "response" => 'No balance'
                                    ]
                                );
                        }
                    } else {    
                        DB::table("sms_history")->where(["id" => $id])
                            ->update(
                                [
                                    "is_retry" => 0,
                                    "response" => 'api not found'
                                ]
                            );
                    }
               }
           }   
        }

        return Command::SUCCESS;
    }

    
    
    
    public function muthoFun($to,$message,$url,$token) {

        $data = ['receiver'=>$to,'message'=>$message,'remove_duplicate'=>true] ;     
           
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST=> 0,
            CURLOPT_SSL_VERIFYPEER=> 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER=> array(
                'Content-Type: application/json',
                'Authorization: '.$token
            )
        ));
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }

    public function smsAPI($url){
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
        $post_response = curl_exec($request);
        curl_close($request);
        return  $post_response;
    }
}
