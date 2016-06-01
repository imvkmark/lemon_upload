<?php namespace Imvkmark\L5DbReverse;

use Illuminate\Support\ServiceProvider;
use Imvkmark\L5DbReverse\Console\MigrationsCommand;
use Laracasts\Generators\GeneratorsServiceProvider;

class L5DbReverseServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = false;

	public function boot() {
		$this->app->register(GeneratorsServiceProvider::class);
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register() {
		$this->app['l5.db-reverse'] = $this->app->share(function ($app) {
			return new MigrationsCommand;
		});
		$this->commands('l5.db-reverse');
	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides() {
		return [];
	}

}
