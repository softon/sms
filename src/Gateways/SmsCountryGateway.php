<?php namespace Softon\Sms\Gateways;




use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SmsCountryGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'http://api.smscountry.com/SMSCwebservice_bulk.aspx?';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode='';

    function __construct()
    {
        $this->gwvars['mobilenumber'] = '';
        $this->gwvars['message'] = '';
        $this->gwvars['User'] = Config::get('sms.smscountry.user');
        $this->gwvars['passwd'] = Config::get('sms.smscountry.passwd');
        $this->gwvars['sid'] = "";
        $this->gwvars['mtype'] = "N";
        $this->gwvars['DR'] = "Y";
        $this->countryCode = Config::get('sms.countryCode');
    }

    public function getUrl()
    {
        foreach($this->gwvars as $key=>$val) {
            $this->request.= $key."=".urlencode($val);
            $this->request.= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;

    }

    public function sendSms($mobile,$message)
    {
        $mobile = $this->addCountryCode($mobile);

        if(is_array($mobile)){
            $mobile = $this->composeBulkMobile($mobile);
        }

        $this->gwvars['mobilenumber'] = $mobile;
        $this->gwvars['message'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        Log::info('SmsCountry Response: '.$this->response);
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