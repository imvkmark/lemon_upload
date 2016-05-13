<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 * @var array
	 */
	protected $listen = [
		'desktop.login_ip_banned'                       => [
			'App\Handlers\Events\Desktop\LoginIpBannedLog',
		],
		'auth.failed'                                   => [
			'App\Handlers\Events\Auth\FailedLog',
		],
		'auth.login'                                    => [
			'App\Handlers\Events\Auth\LoginLog',
			'App\Handlers\Events\Auth\LoginNum',
		],
		'SocialiteProviders\Manager\SocialiteWasCalled' => [
			'SocialiteProviders\Qq\QqExtendSocialite',
		],
	];

	/**
	 * Register any other events for your application.
	 * @param  \Illuminate\Contracts\Events\Dispatcher $events
	 * @return void
	 */
	public function boot(DispatcherContract $events) {
		parent::boot($events);

		//
	}

}
