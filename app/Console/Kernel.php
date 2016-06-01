<?php
namespace App\Console;

use App\Console\Commands\Lemon\Fe as LemonFe;
use App\Console\Commands\Lemon\Rbac as LemonRbac;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 * @var array
	 */
	protected $commands = [
		LemonFe::class,
		LemonRbac::class,
	];

	/**
	 * Define the application's command schedule.
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		
	}
}
