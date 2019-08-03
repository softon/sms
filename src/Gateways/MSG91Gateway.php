<?php namespace Softon\Sms\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MSG91Gateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'https://api.msg91.com/api/sendhttp.php?';
    protected $request = '';
    public $status = false;
    public $response = '';

    function __construct()
    {
        $this->gwvars['mobiles'] = '';
        $this->gwvars['message'] = '';
        $this->gwvars['country'] = Config::get('sms.msg91.country');
        $this->gwvars['sender'] = Config::get('sms.msg91.sender');
        $this->gwvars['route'] = Config::get('sms.msg91.route');
        $this->gwvars['authkey'] = Config::get('sms.msg91.authkey');
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
        if(is_array($mobile)){
            $mobile = $this->composeBulkMobile($mobile);
        }

        $this->gwvars['mobiles'] = $mobile;
        $this->gwvars['message'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        Log::info('MSG91 SMS Response: '.$this->response);
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
     * Check Response
     * @return array
     */
    public function response(){
       return $this->response;
    }




}