<?php namespace Softon\Sms\Gateways;




use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ClickatellGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'http://api.clickatell.com/http/sendmsg?';
    protected $request = '';
    public $status = false;
    public $response = '';

    function __construct()
    {
        $this->gwvars['to'] = '';
        $this->gwvars['text'] = '';
        $this->gwvars['api_id'] = Config::get('sms.clickatell.api_id');
        $this->gwvars['user'] = Config::get('sms.clickatell.user');
        $this->gwvars['password'] = Config::get('sms.clickatell.password');
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

        $this->gwvars['to'] = $mobile;
        $this->gwvars['text'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        Log::info('Clickatell SMS Response: '.$this->response);
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