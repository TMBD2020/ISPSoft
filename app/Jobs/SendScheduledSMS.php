<?php

namespace App\Jobs;

use App\Models\TmbdUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendScheduledSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sms;

    public function __construct($sms)
    {
        $this->sms = $sms;
    }

    public function handle()
    {
        $sms = $this->sms;
        $user = TmbdUsers::find($sms->company_id);

        if (!$user || !$user->sms_api_id) {
            return $this->markFailed($sms->id, 'User or API missing');
        }

        $api = DB::table('sms_api')->find($user->sms_api_id);

        if (!$api) {
            return $this->markFailed($sms->id, 'API not found');
        }

        $smsRate = $user->sms_sender_type == 1 ? $user->masking_rate : $user->non_masking_rate;
        $smsCost = $smsRate * $sms->sms_count;

        if ($user->sms_balance < $smsCost) {
            return $this->markFailed($sms->id, 'Insufficient balance');
        }

        if (!preg_match("/^01[3-9][0-9]{8}$/", $sms->sms_receiver)) {
            return $this->markFailed($sms->id, 'Invalid number');
        }

        $response = $this->sendSms($sms->sms_receiver, $sms->sms_text, $api);

        $user->sms_balance -= $smsCost;
        $user->last_sms_sent = now();
        $user->save();

        DB::table('sms_history')->where('id', $sms->id)->update([
            'is_retry' => 0,
            'sms_status' => 'Sent',
            'sent_time' => now(),
            'response' => $response,
        ]);
    }

    protected function sendSms($to, $message, $api)
    {
        if ($api->id == 3) {
            return $this->sendViaMutho($to, $message, $api->api_url, $api->api_token);
        }

        $params = ($api->id == 1)
            ? "api_token={$api->api_token}&senderid={$api->api_sender}&contact_number=$to&message=$message"
            : "api_token={$api->api_token}&recipient=$to&senderid={$api->api_sender}&type=plain&message=$message";

        $curl = curl_init($api->api_url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    protected function sendViaMutho($to, $message, $url, $token)
    {
        $payload = json_encode([
            'receiver' => $to,
            'message' => $message,
            'remove_duplicate' => true,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: $token"
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    protected function markFailed($smsId, $reason)
    {
        DB::table('sms_history')->where('id', $smsId)->update([
            'is_retry' => 0,
            'sms_status' => 'Failed',
            'response' => $reason,
        ]);
    }
}
