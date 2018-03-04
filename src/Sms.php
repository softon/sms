<?php namespace Softon\Sms;

use Softon\Sms\Gateways\ClickatellGateway;
use Softon\Sms\Gateways\CustomGateway;
use Softon\Sms\Gateways\GupshupGateway;
use Softon\Sms\Gateways\ItexmoGateway;
use Softon\Sms\Gateways\LogGateway;
use Softon\Sms\Gateways\MockerGateway;
use Softon\Sms\Gateways\MVaayooGateway;
use Softon\Sms\Gateways\SmsAchariyaGateway;
use Softon\Sms\Gateways\SmsCountryGateway;
use Softon\Sms\Gateways\SmsGatewayInterface;
use Softon\Sms\Gateways\SmsLaneGateway;
use Softon\Sms\Gateways\NexmoGateway;

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

    public function gateway($name)
    {
        // Gateways : Log / Clickatell / Gupshup / MVaayoo / SmsAchariya / SmsCountry / SmsLane / Nexmo / Mocker/ Custom
        switch($name)
        {
            case 'Log':
                $this->gateway = new LogGateway();
                break;
            case 'Clickatell':
                $this->gateway = new ClickatellGateway();
                break;
            case 'Gupshup':
                $this->gateway = new GupshupGateway();
                break;
            case 'Itexmo':
                $this->gateway = new ItexmoGateway();
            case 'MVaayoo':
                $this->gateway = new MVaayooGateway();
                break;
            case 'SmsAchariya':
                $this->gateway = new SmsAchariyaGateway();
                break;
            case 'SmsCountry':
                $this->gateway = new SmsCountryGateway();
                break;
            case 'SmsLane':
                $this->gateway = new SmsLaneGateway();
                break;
            case 'Nexmo':
                $this->gateway = new NexmoGateway();
                break;
            case 'Mocker':
                $this->gateway = new MockerGateway();
                break;
            case 'Custom':
                $this->gateway = new CustomGateway();
                break;
        }
        return $this;
    }
}