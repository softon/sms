<?php namespace Softon\Sms\Gateways;




use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SmsAchariyaGateway implements SmsGatewayInterface {

    protected $gwvars = array();
    protected $url = '';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode='';

    function __construct()
    {
        $this->gwvars['uid'] = Config::get('sms.smsachariya.uid');
        $this->gwvars['pin'] = Config::get('sms.smsachariya.pin');
        $this->gwvars['sender'] = '';
        $this->gwvars['route'] = '0';
        $this->gwvars['mobile'] = '';
        $this->gwvars['message'] = '';
        $this->gwvars['push_id'] = 1;
        $this->url = "http://".Config::get('sms.smsachariya.domain')."/api/sms.php?";
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

        $this->gwvars['mobile'] = $mobile;
        $this->gwvars['message'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(),['body'=>$this->gwvars])->getBody()->getContents();
        Log::info('SMS Achariya Response: '.$this->response);
        return $this;
    }


    /**
     * Set the Route
     * @param int $route
     * @return $this
     */
    public function route($route=0){
        $this->gwvars['route'] = $route;
        return $this;
    }


    /**
     * Set the Sender ID
     * @param $sender
     * @return $this
     */
    public function sender($sender){
        $this->gwvars['sender'] = $sender;
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
        $client = new \GuzzleHttp\Client();
        $report = $client->post('http://sms.djtma.com/api/dlr.php?',[
            'body'=>[
                'uid'=>$this->gwvars['uid'],
                'pin'=>$this->gwvars['pin'],
                'msgid'=>$this->response
            ]])->getBody()->getContents();
        $report = trim($report,',');
        Log::info('SMS Achariya Delivery Report: '.$report);
        $exrepos = explode(',',$report);
        $sent = 0;
        $delivered = 0;
        $dnd = 0;
        $error = 0;
        foreach($exrepos as $exrepo){
            if($exrepo=='Sent'){
                $sent++;
            }elseif($exrepo=='Delivered'){
                $delivered++;
            }elseif($exrepo=='DND'){
                $dnd++;
            }else{
                $error++;
            }
        }

        return ['status'=>[
            'sent'=>$sent,'delivered'=>$delivered,'dnd'=>$dnd,'error'=>$error
        ],'response'=>$this->response,'report'=>$report,'mobile'=>$this->gwvars['mobile']];
    }




}