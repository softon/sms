<?php namespace Softon\Sms;

use Softon\Sms\Gateways\SmsGatewayInterface;

class Sms {

    protected $gateway;
    protected $view;

    /**
     * @param SmsGatewayInterface $gateway
     * @param SmsViewInterface $view
     */
    function __construct(SmsGatewayInterface $gateway,SmsViewInterface $view)
    {
        $this->gateway = $gateway;
        $this->view = $view;
    }

    public function send($mobile,$view,$params=[]){

        $message = $this->view->getView($view,$params)->render();
        return $this->gateway->sendSms($mobile,$message);
    }

    public function send_raw($mobile,$message){
        return $this->gateway->sendSms($mobile,$message);
    }
}