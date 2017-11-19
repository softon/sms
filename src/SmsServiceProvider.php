<?php namespace Softon\Sms;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $gateway = Config::get('sms.gateway');
        $view    = Config::get('sms.view');
        $this->app->bind('sms', 'Softon\Sms\Sms');

        $this->app->bind('Softon\Sms\Gateways\SmsGatewayInterface','Softon\Sms\Gateways\\'.$gateway.'Gateway');
        $this->app->bind('Softon\Sms\SmsViewInterface','Softon\Sms\Sms'.$view.'View');
	}


    public function boot(){
        $this->publishes([
            __DIR__.'/views/sms' => base_path('resources/views/sms'),
            __DIR__.'/config/config.php' => base_path('config/sms.php'),
        ]);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [

        ];
	}

}
