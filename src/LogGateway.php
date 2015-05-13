<?php namespace Softon\Sms;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class LogGateway implements SmsGatewayInterface {



    function __construct()
    {

    }

    public function getUrl()
    {
        return true;

    }

    public function sendSms($mobile,$message)
    {
        $gwvars['send_to'] = '91'.$mobile;
        $gwvars['msg'] = $message;
        Log::info('SMS Sent!', $gwvars);
    }




}