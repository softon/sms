<?php namespace Softon\Sms\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class LogGateway implements SmsGatewayInterface {


    public $countryCode='';
    public $response='';
    public $status=false;

    function __construct()
    {
        $this->countryCode = Config::get('sms.countryCode');
    }

    public function getUrl()
    {
        return true;

    }

    public function sendSms($mobile,$message)
    {
        $mobile = $this->addCountryCode($mobile);

        if(is_array($mobile)){
            $mobile = $this->composeBulkMobile($mobile);
        }

        $gwvars['send_to'] = $mobile;
        $gwvars['msg'] = $message;
        Log::info('SMS Saved to Log: ', $gwvars);
        $this->status = true;
        $this->response = 'Saved to Log File.';
        return $this;
    }

    /**
     * Create Send to Mobile for Bulk Messaging
     * @param $mobile
     * @return string
     */
    private function composeBulkMobile($mobile)
    {
        return implode(',',$mobile);
    }

    /**
     * Prepending Country Code to Mobile Numbers
     * @param $mobile
     * @return array|string
     */
    private function addCountryCode($mobile)
    {
        if(is_array($mobile)){
            array_walk($mobile, function(&$value, $key) { $value = $this->countryCode.$value; });
            return $mobile;
        }

        return $this->countryCode.$mobile;
    }


    public function response()
    {
        return $this->response;
    }


}