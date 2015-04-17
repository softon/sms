<?php namespace Softon\Sms;


class SmsFileView implements SmsViewInterface {

    public function getView($view,$params)
    {
        return view($view,$params);
    }
}