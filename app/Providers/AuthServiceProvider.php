<?php namespace App\Providers;

use App\Lemon\Repositories\Application\ApiAuthGuard;
use App\Lemon\Repositories\Providers\AuthProvider;
use Illuminate\Support\ServiceProvider;


class AuthServiceProvider extends ServiceProvider {

	protected $policies = [

	];


	public function register() {
		\Auth::extend('lemon.auth', function ($app) {
			$model = $app['config']['auth.model'];
			return new AuthProvider($model);
		});

		$this->app->bind('lemon.auth.api', function ($app) {
			$model    = $app['config']['auth.model'];
			$provider = new AuthProvider($model);
			$guard    = new ApiAuthGuard($provider, $app['session.store'], $app['request']);
			$guard->setDispatcher($this->app['events']);
			$guard->setCookieJar($app['cookie']);
			return $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
		});
	}

	public function provides() {
		return ['lemon.auth.api'];
	}
}
