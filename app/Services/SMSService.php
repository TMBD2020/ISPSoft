<?php
namespace App\Services;

use App\Models\SmsHistory;
use App\Models\TmbdUsers;
use Instasent\SMSCounter\SMSCounter;
class SMSService{
    protected $smsCounter;
    public function __construct(
        SMSCounter $smsCounter
    ) {
        $this->smsCounter = $smsCounter;
    }
    public function getAPIid(){
       return TmbdUsers::find(\Settings::company_id())->sms_api_id;
    }
    public function masterSave($sms_data){
        $company_id     = \Settings::company_id();
        $cell_no        = $sms_data["to"];
        $sms_text       = $sms_data["sms_text"];
        $sms_count      = $this->smsCounter->count($sms_text)->messages;
        $sms_from       = $sms_data["sms_from"];
        $sms_sender     = $sms_data["sms_sender"];
        $sms_type       = $this->smsCounter->count($sms_text)->encoding;
        $sms_status     = "Pending";
        $created_at     = date("Y-m-d H:i");
        $schedule_time  = $sms_data["schedule_time"];

        $sms_schedule_time = date("Y-m-d H:i",strtotime($schedule_time));
        if(strtotime($schedule_time)>strtotime($created_at)){
            $sent_time=$sms_schedule_time;
        }else{
            $sent_time = $sms_schedule_time;
        }

        $data = [
            "company_id"=>$company_id,
            "sms_receiver"=>$cell_no,
            "sms_sender"=>$sms_sender,
            "sms_count"=>$sms_count,
            "sms_type"=>$sms_type,
            "client_type"=>$sms_from,
            "sms_text"=>$sms_text,
            "sms_status"=>$sms_status,
            "sms_schedule_time"=>$sms_schedule_time,
            "sent_time"=>$sent_time,
            "sms_api"=>$this->getAPIid(),
            "created_at"=>$created_at
        ];

        $result = SmsHistory::query()->insert($data);

       return $result;
    }
}