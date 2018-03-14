<?php namespace Softon\Sms\Gateways;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ItexmoGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = '';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode='';

    function __construct()
    {
        $this->url = 'https://www.itexmo.com/php_api/api.php';
        $this->gwvars['3'] = Config::get('sms.itexmo.api_code');
        $this->countryCode = Config::get('sms.countryCode');
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function sendSms($mobile,$message)
    {
        $mobile = Config::get('sms.custom.add_code')?$this->addCountryCode($mobile):$mobile;

        if(is_array($mobile)){
            $mobile = $this->composeBulkMobile($mobile);
        }

        $this->gwvars['1'] = $mobile;
        $this->gwvars['2'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(), ['form_params'=>$this->gwvars])->getBody()->getContents();
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

    /**
     * Check Response
     * @return array
     */
    public function response(){
        return $this->response;
    }




}