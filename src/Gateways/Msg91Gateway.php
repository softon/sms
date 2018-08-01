<?php namespace Softon\Sms\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Msg91Gateway implements SmsGatewayInterface {

    protected $client;
    protected $gwvars = [];
    protected $url = 'http://api.msg91.com/api/';

    function __construct()
    {
        $this->gwvars['authkey'] = Config::get('sms.msg91.authkey');
        $this->gwvars['sender_id'] = Config::get('sms.msg91.sender_id');
        $this->gwvars['route'] = Config::get('sms.msg91.sms_route');
        $this->gwvars['otp_length'] = Config::get('sms.msg91.otp_length');
        $this->gwvars['otp_expiry'] = Config::get('sms.msg91.otp_expiry');
        $this->gwvars['countryCode'] = Config::get('sms.countryCode');
        $this->client = new Client([
			'base_uri' => $this->url,
			'http_errors' => false
		]);
    }

    public function getUrl()
    {
        return;
    }

    public function sendSms($mobile,$message)
    {
        if(!is_array($mobile)){
            $mobile = array($mobile);
        }
        $response = $this->client->request('POST','v2/sendsms', [
            'json' => [
                'sender' => $this->gwvars['sender_id'],
                'route' => $this->gwvars['route'],
                'sms' => [
                    [
                        'message' => $message,
                        'to' => $mobile
                    ]
                ]
            ],
            'headers' => [
                'authkey' => $this->gwvars['authkey'],
                'Content-Type' => 'application/json'
            ]
        ]);
        $this->response = $response->getBody()->getContents();
        return $this;
    }

    public function sendOTP($mobile,$message)
    {
        $response = $this->client->request('POST','sendotp.php', [
            'form_params' => [
                'authkey' => $this->gwvars['authkey'],
                'message' => $message,
                'sender' => $this->gwvars['sender_id'],
                'mobile' => $mobile,
                'otp_length' => $this->gwvars['otp_length'],
                'otp_expiry' => $this->gwvars['otp_expiry']
            ]
        ]);
        $this->response = $response->getBody()->getContents();
        return $this;

    }

    public function verifyOTP($mobile,$otp)
    {
        $response = $this->client->request('POST','verifyRequestOTP.php', [
            'form_params' => [
                'authkey' => $this->gwvars['authkey'],
                'mobile' => $mobile,
                'otp' => $otp
            ]
        ]);
        $this->response = $response->getBody()->getContents();
        return $this;
    }

    public function response(){
        return json_decode($this->response);
    }

}