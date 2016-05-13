<?php namespace App\Providers;

/**
 * 清除 clockwork 的 json 文件
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */
use Illuminate\Support\ServiceProvider;

class ClockworkClearServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 * @return void
	 */
	public function boot() {
		$files        = glob(storage_path('clockwork/') . '*.json');
		$delete       = count($files) - 40;
		$deletedFiles = array_slice($files, 0, $delete);
		foreach ($deletedFiles as $file) {
			@unlink($file);
		}
	}

	public function register() {

	}

}
