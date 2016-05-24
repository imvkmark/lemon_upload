<?php namespace App\Handlers\Events\Auth;

use App\Models\PamAccount;

class LoginNum {

	/**
	 * Create the event handler.
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 * @param $user PamAccount
	 */
	public function handle($user) {
		PamAccount::where('account_id', $user->account_id)->increment('login_times', 1);
	}

}
