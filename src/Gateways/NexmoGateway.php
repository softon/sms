<?php namespace Softon\Sms\Gateways;




use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class NexmoGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'https://rest.nexmo.com/sms/json';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode='';

    function __construct()
    {
        $this->gwvars['to'] = '';
        $this->gwvars['text'] = '';
        $this->gwvars['api_key'] = Config::get('sms.nexmo.api_key');
        $this->gwvars['api_secret'] = Config::get('sms.nexmo.api_secret');
        $this->gwvars['from'] = "NEXMO";
        $this->countryCode = Config::get('sms.countryCode');
    }

    public function getUrl()
    {
        foreach($this->gwvars as $key=>$val) {
            $this->request.= $key."=".urlencode($val);
            $this->request.= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url;    //.$this->request;

    }

    public function sendSms($mobile,$message)
    {
        $mobile = $this->addCountryCode($mobile);

        if(is_array($mobile)){
            $this->composeBulkMobile($mobile, $message);
        } else {
            $this->composeSingleMobile($mobile, $message);
        }


        return $this;

    }

    /**
     * Create Send to Mobile for Bulk Messaging
     * @param $mobile
     * @return string
     */
    private function composeSingleMobile($mobile, $message)
    {
        $this->gwvars['to'] = $mobile;
        $this->gwvars['text'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(),['form_params'=>$this->gwvars])->getBody()->getContents();
        Log::info('Nexmo SMS Response: '.$this->response);
        return $this->response;
    }

    /**
     * Create Send to Mobile for Bulk Messaging
     * @param $mobile
     * @return string
     */
    private function composeBulkMobile($mobile, $message)
    {
        foreach ($mobile as $mobile_no) {
            $this->composeSingleMobile($mobile_no, $message);
        }
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
        $response = json_decode($this->response);

        return $response;
    }




}