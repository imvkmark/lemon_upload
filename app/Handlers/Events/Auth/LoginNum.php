<?php namespace App\Handlers\Events\Auth;

use App\Models\PamAccount;

class LoginNum {

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
		PamAccount::where('account_id', $user->account_id)->increment('login_times', 1);
	}

}
