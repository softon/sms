<?php namespace Softon\Sms;




use Illuminate\Support\Facades\Config;

class GupshupGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'http://enterprise.smsgupshup.com/GatewayAPI/rest?';
    protected $request = '';
    public $status = '';

    function __construct()
    {
        $this->gwvars['send_to'] = '';
        $this->gwvars['msg'] = '';
        $this->gwvars['method'] = 'sendMessage';
        $this->gwvars['userid'] = Config::get('sms.gupshup.userid');
        $this->gwvars['password'] = Config::get('sms.gupshup.password');
        $this->gwvars['v'] = "1.1";
        $this->gwvars['msg_type'] = "TEXT";
        $this->gwvars['auth_scheme'] = "PLAIN";
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
        $this->gwvars['send_to'] = '91'.$mobile;
        $this->gwvars['msg'] = $message;
        $client = new \GuzzleHttp\Client();
        $response = $client->get($this->getUrl());
        $this->status = $response->getBody();
        $status = explode('|',$this->status);
        if(trim($status[0])=='success'){
            return true;
        }else{
            return false;
        }
    }




}