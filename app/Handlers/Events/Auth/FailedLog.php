<?php namespace App\Handlers\Events\Auth;

/**
 * 失败日志
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 Sour Lemon Team
 */
use App\Lemon\Repositories\Sour\LmEnv;
use App\Models\PamAccount;
use App\Models\PamLog;

class FailedLog {

	/**
	 * Create the event handler.
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 * @param $credentials
	 */
	public function handle($credentials) {
		$account      = PamAccount::getByAccountName($credentials['account_name']);
		$account_id   = '';
		$account_name = $credentials['account_name'];
		$account_type = '';
		if ($account) {
			$account_id   = $account['account_id'];
			$account_type = $account['account_type'];
		}
		$content = '尝试登陆失败, 用户信息不匹配';
		if ($account_type != $credentials['account_type']) {
			$content = '范围[' . $account_type . ']用户跨域登陆, 登陆失败';
		}
		PamLog::create([
			'account_id'   => $account_id,
			'account_name' => $account_name,
			'account_type' => $account_type,
			'log_type'     => 'error',
			'log_ip'       => LmEnv::ip(),
			'log_content'  => $content,
		]);
	}

}
