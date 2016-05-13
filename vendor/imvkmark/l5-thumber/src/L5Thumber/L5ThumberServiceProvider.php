<?php namespace Imvkmark\L5Thumber;

use Illuminate\Support\ServiceProvider;
use Imvkmark\L5Thumber\Eva\Config\Config;

class L5ThumberServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 * @return void
	 */
	public function boot() {
		$this->publishes([
			__DIR__ . '/../config/thumber.php' => config_path('l5-thumber.php'),
		], 'sour-lemon');
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register() {
		// 路由
		if (!$this->app->routesAreCached()) {
			require __DIR__ . '/../routes.php';
		}
		$this->mergeConfigFrom(__DIR__ . '/../config/thumber.php', 'l5-thumber');

		$this->app->bind('l5.thumber.config', function ($app) {
			$config = $app->config->get('l5-thumber');
			$app['l5.thumber.config'] = new Config($config);
			return $app['l5.thumber.config'];
		});
	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides() {
		return [];
	}

}
