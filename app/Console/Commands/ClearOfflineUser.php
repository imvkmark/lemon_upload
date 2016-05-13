<?php namespace App\Console\Commands;

use App\Lemon\Dailian\Action\ActionAccount;
use Illuminate\Console\Command;

class ClearOfflineUser extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'lemon:clear-offline-user';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Clear Offline User';

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		$Account = new ActionAccount();
		$Account->clearOffine();
		$this->info('Clear Offline User!');
	}
}
