<?php namespace Softon\Sms\Gateways;




use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MVaayooGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = 'http://api.mVaayoo.com/mvaayooapi/MessageCompose?';
    protected $request = '';
    public $response = '';
    public $status = false;
    public $countryCode='';

    function __construct()
    {
        $this->gwvars['receipientno'] = '';
        $this->gwvars['msgtxt'] = '';
        $this->gwvars['senderID'] = Config::get('sms.mvaayoo.senderID');
        $this->gwvars['user'] = Config::get('sms.mvaayoo.user');
        $this->gwvars['msgtype'] = 0;
        $this->gwvars['state'] = 4;
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

        $this->gwvars['receipientno'] = $mobile;
        $this->gwvars['msgtxt'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        Log::info('MVaayoo SMS Response: '.$this->response);

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
        $status = explode(',',$this->response);
        if(trim($status[0])=='Status=0'){
            $this->status = true;
        }

        return ['status'=>$this->status,'response'=>$this->response];
    }

}