<?php
namespace App\Console;

use App\Console\Commands\ClearExpiredApiToken;
use App\Console\Commands\ClearOfflineUser;
use App\Console\Commands\Lemon\Fe as LemonFe;
use App\Console\Commands\Lemon\Rbac as LemonRbac;
use App\Console\Commands\OverGameOrder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 * @var array
	 */
	protected $commands = [
		LemonFe::class,
		OverGameOrder::class,
		ClearExpiredApiToken::class,
		ClearOfflineUser::class,
		LemonRbac::class,
	];

	/**
	 * Define the application's command schedule.
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		$schedule->command('lemon:over-game-order')
			->everyMinute()
			->sendOutputTo(storage_path('console/over-game-order.log'));
		$schedule->command('lemon:clear-expired-api-token')
			->everyThirtyMinutes()
			->sendOutputTo(storage_path('console/clear-expired-api-token.log'));
		$schedule->command('lemon:clear-offline-user')
			->everyThirtyMinutes()
			->sendOutputTo(storage_path('console/clear-offline-user.log'));

		// 备份数据库, 每天两次
		$schedule->command('backup:run --only-db')
			->twiceDaily()
			->sendOutputTo(storage_path('console/backup_run.log'));
		// 每周备份一次
		$schedule->command('backup:run --only-files')
			->sundays()->weekly()->at('00:00')
			->sendOutputTo(storage_path('console/backup_run.log'));
	}
}
