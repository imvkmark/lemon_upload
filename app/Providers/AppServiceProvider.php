<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 * @return void
	 */
	public function boot() {
		// 系统快速帮助
		require_once app_path('Lemon/Repositories/Helper/helper.php');
		// 项目函数
		require_once app_path('Lemon/Upload/Helper/helper.php');
	}

	/**
	 * Register any application services.
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 * @return void
	 */
	public function register() {

	}

}
