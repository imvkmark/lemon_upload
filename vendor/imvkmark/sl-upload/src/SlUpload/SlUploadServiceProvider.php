<?php namespace Imvkmark\SlUpload;

use Illuminate\Support\ServiceProvider;
use Imvkmark\SlUpload\Commands\MigrationCommand;

class SlUploadServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = false;

	public function boot() {
		// 发布 config 文件, 在命令行中使用 --tag=sour-lemon 来确认配置文件
		$this->publishes([
			__DIR__ . '/../config/config.php'     => config_path('sl-upload.php'), // config
			__DIR__ . '/../acl/dsk_image_key.php' => lemon_path('Suit/Acl/Desktop/dsk_image_key.php'), // acl
		], 'sour-lemon');

		// 路由
		if (!$this->app->routesAreCached()) {
			require __DIR__ . '/../routes.php';
		}

		// Register commands
		$this->commands('command.lemon.upload_migration');

		// 定义视图命名空间
		if (method_exists($this, 'loadViewsFrom')) {
			$this->loadViewsFrom(__DIR__ . '/../views', 'sl-upload');
		}
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register() {
		$this->registerCommands();

		$this->mergeConfig();
	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides() {
		return [];
	}


	/**
	 * Register the artisan commands.
	 * @return void
	 */
	private function registerCommands() {
		$this->app->singleton('command.lemon.upload_migration', function ($app) {
			return new MigrationCommand();
		});
	}

	/**
	 * Merges user's and sl-upload's configs.
	 * @return void
	 */
	private function mergeConfig() {
		$this->mergeConfigFrom(
			__DIR__ . '/../config/config.php', 'sl-upload'
		);
	}

}
