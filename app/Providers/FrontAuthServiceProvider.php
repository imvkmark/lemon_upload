<?php namespace App\Providers;

use App\Lemon\Extensions\AuthProvider;
use App\Lemon\Extensions\FrontAuthGuard;
use Illuminate\Support\ServiceProvider;


class FrontAuthServiceProvider extends ServiceProvider {

	public function register() {

		$this->app->bind('lemon.extensions.front_auth', function ($app) {
			$model    = $app['config']['auth.model'];
			$provider = new AuthProvider($model);
			$guard    = new FrontAuthGuard($provider, $app['session.store'], $app['request']);
			$guard->setCookieJar($app['cookie']);
			$guard->setDispatcher($this->app['events']);
			return $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
		});

	}

}
