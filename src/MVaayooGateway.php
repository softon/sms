<?php namespace Softon\Sms;




use Illuminate\Support\Facades\Config;

class MVaayooGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'http://api.mVaayoo.com/mvaayooapi/MessageCompose?';
    protected $request = '';
    public $status = '';

    function __construct()
    {
        $this->gwvars['receipientno'] = '';
        $this->gwvars['msgtxt'] = '';
        $this->gwvars['senderID'] = Config::get('sms.gupshup.senderID');
        $this->gwvars['user'] = Config::get('sms.gupshup.user');
        $this->gwvars['msgtype'] = 0;
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
        $status = explode(',',$this->status);
        if($status[0]=='Status=0'){
            return true;
        }else{
            return false;
        }
    }




}