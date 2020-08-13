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
use Softon\Sms\Gateways\MSG91Gateway;
use Softon\Sms\Gateways\TwilioGateway;

class Sms
{

    protected $gateway;
    protected $view;

    /**
     * @param SmsGatewayInterface $gateway
     * @param SmsViewInterface $view
     */
    function __construct(SmsGatewayInterface $gateway, SmsViewInterface $view)
    {
        $this->gateway = $gateway;
        $this->view = $view;
    }

    function __call($name_of_function, $arguments)
    {

        // It will match the function name 
        if ($name_of_function == 'send') {

            switch (count($arguments)) {

                // If there is only one argument 
                // area of circle 
                case 2:
                    return $this->gateway->sendSms($arguments[0], $arguments[1]);

                // IF two arguments then area is rectangel; 
                case 3:
                    $message = $this->view->getView($arguments[1], $arguments[2])->render();
                    return $this->gateway->sendSms($arguments[0], $message);
            }
        }
    }

    public function send_raw($mobile, $message)
    {
        return $this->gateway->sendSms($mobile, $message);
    }

    public function gateway($name)
    {
        $name = strtolower($name);
        switch ($name) {
            case 'log':
                $this->gateway = new LogGateway();
                break;
            case 'clickatell':
                $this->gateway = new ClickatellGateway();
                break;
            case 'gupshup':
                $this->gateway = new GupshupGateway();
                break;
            case 'itexmo':
                $this->gateway = new ItexmoGateway();
            case 'mvaayoo':
                $this->gateway = new MVaayooGateway();
                break;
            case 'smsachariya':
                $this->gateway = new SmsAchariyaGateway();
                break;
            case 'smscountry':
                $this->gateway = new SmsCountryGateway();
                break;
            case 'smslane':
                $this->gateway = new SmsLaneGateway();
                break;
            case 'nexmo':
                $this->gateway = new NexmoGateway();
                break;
            case 'mocker':
                $this->gateway = new MockerGateway();
                break;
            case 'msg91':
                $this->gateway = new MSG91Gateway();
                break;
            case 'twilio':
                $this->gateway = new TwilioGateway();
                break;
            case 'custom':
                $this->gateway = new CustomGateway();
                break;
        }
        return $this;
    }
}