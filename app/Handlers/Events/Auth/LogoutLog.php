<?php namespace App\Handlers\Events\Auth;

use App\Lemon\Repositories\Sour\LmEnv;
use App\Models\PamLog;

class logoutLog {

	/**
	 * Create the event handler.
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 * @return void
	 */
	public function handle($user) {
		PamLog::create([
			'account_id'   => $user->account_id,
			'account_name' => $user->account_name,
			'account_type' => $user->account_type,
			'log_type'     => 'success',
			'log_ip'       => LmEnv::ip(),
			'log_content'  => '登出系统',
		]);
	}

}
