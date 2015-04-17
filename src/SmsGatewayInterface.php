<?php namespace Softon\Sms;

interface SmsGatewayInterface {
    public function getUrl();
    public function sendSms($mobile,$message);
}