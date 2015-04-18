<?php namespace Softon\Sms\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade {

    protected static function getFacadeAccessor() { return 'sms'; }

}