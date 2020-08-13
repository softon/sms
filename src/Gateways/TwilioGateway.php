<?php


namespace Softon\Sms\Gateways;

/**
 *
 * @Date 01/08/20
 * @author  JithinVijayan <jithin@linways.com>
 */
class TwilioGateway implements SmsGatewayInterface
{
    protected $gwvars = array();
    protected $url = '';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode = '';
    public $password = '';

    function __construct()
    {
        $this->gwvars['To'] = '';
        $this->gwvars['Body'] = '';
        $this->gwvars['From'] = Config::get('sms.twilio.from');
        $this->gwvars['token'] = Config::get('sms.twilio.token');
        $this->url = 'https://api.twilio.com/2010-04-01/Accounts/' . Config::get('sms.twilio.account_id') . '/Messages.json';
        $this->countryCode = Config::get('sms.countryCode');
        $this->password = base64_encode(Config::get('sms.twilio.userId') . ":" . Config::get('sms.twilio.token'));
    }

    public function getUrl()
    {
        foreach ($this->gwvars as $key => $val) {
            $this->request .= $key . "=" . urlencode($val);
            $this->request .= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request) - 1);
        return $this->url;    //.$this->request;

    }

    public function sendSms($mobile, $message)
    {
        $mobile = $this->addCountryCode($mobile);

        if (is_array($mobile)) {
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
        $this->gwvars['To'] = $mobile;
        $this->gwvars['Body'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(), ['headers' => [
            'Authorization' => 'Basic ' . $this->password,
        ], 'form_params' => $this->gwvars])->getBody()->getContents();
        Log::info('Twilio SMS Response: ' . $this->response);
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
        if (is_array($mobile)) {
            array_walk($mobile, function (&$value, $key) {
                $value = $this->countryCode . " " . $value;
            });
            return $mobile;
        }

        return $this->countryCode ." ". $mobile;
    }


    /**
     * Check Response
     * @return array
     */
    public function response()
    {
        $response = json_decode($this->response);

        return $response;
    }
}