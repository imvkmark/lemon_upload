<?php

namespace App\Console\Commands;

use App\Lemon\Repositories\Sour\LmEnv;
use App\Models\ApiInit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearExpiredApiToken extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'lemon:clear-expired-api-token';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Clear Api access token when it is expired!';

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		$apiExpiredAt = Carbon::createFromTimestamp(LmEnv::time() - config('api.time_expire') * 3600)->toDateTimeString();
		$expired      = ApiInit::where('updated_at', '<', $apiExpiredAt)->lists('access_token');
		if ($expired && is_array($expired)) {
			ApiInit::destroy($expired);
		}
	}
}
