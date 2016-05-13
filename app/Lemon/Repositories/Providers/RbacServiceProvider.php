<?php namespace App\Lemon\Repositories\Providers;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 * @license MIT
 * @package Zizaco\Entrust
 */

use App\Lemon\Repositories\Application\Rbac\Rbac;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider {

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

		// Register blade directives
		$this->bladeDirectives();
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register() {
		$this->registerEntrust();
	}

	/**
	 * Register the blade directives
	 * @return void
	 */
	private function bladeDirectives() {
		// Call to Entrust::hasRole
		\Blade::directive('role', function ($expression) {
			return "<?php if (\\Rbac::hasRole{$expression}) : ?>";
		});

		\Blade::directive('endrole', function ($expression) {
			return "<?php endif; // Rbac::hasRole ?>";
		});

		// Call to Entrust::can
		\Blade::directive('permission', function ($expression) {
			return "<?php if (\\Rbac::capable{$expression}) : ?>";
		});

		\Blade::directive('endpermission', function ($expression) {
			return "<?php endif; // Rbac::capable ?>";
		});

		// Call to Entrust::ability
		\Blade::directive('ability', function ($expression) {
			return "<?php if (\\Rbac::ability{$expression}) : ?>";
		});

		\Blade::directive('endability', function ($expression) {
			return "<?php endif; // Rbac::ability ?>";
		});
	}

	/**
	 * Register the application bindings.
	 * @return void
	 */
	private function registerEntrust() {
		$this->app->bind('lemon.rbac', function ($app) {
			return new Rbac($app);
		});

		$this->app->alias('lemon.rbac', 'App\Lemon\Repositories\Application\Rbac\Rbac');
	}

	public function provides() {
		return [
			'lemon.rbac',
		];
	}


}
