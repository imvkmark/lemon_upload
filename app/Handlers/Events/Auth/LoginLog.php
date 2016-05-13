<?php namespace App\Handlers\Events\Auth;

use App\Lemon\Repositories\Sour\LmEnv;
use App\Models\AccountFront;
use App\Models\PamAccount;
use App\Models\PamLog;
use App\Models\PluginArea;

class LoginLog {


	/**
	 * Handle the event.
	 * @return void
	 */
	public function handle($user) {
		// 后台授权登录不计入日志
		if (\Session::has('desktop_auth')) {
			return ;
		}
		$accountType = PamAccount::userType($user->account_id);
		$parentId    = 0;
		if ($accountType == PamAccount::FRONT_SUBUSER) {
			$parentId = AccountFront::where('account_id', $user->account_id)->value('parent_id');
		}
		$ip       = LmEnv::ip();
		$areaText = app('l5.ip')->area($ip);
		$areaId   = PluginArea::toAreaid($areaText);
		if ($areaId) {
			$areaName = PluginArea::getCache($areaId)['area_name'];
		} else {
			$areaName = '';
		}
		PamLog::create([
			'account_id'    => $user->account_id,
			'account_name'  => $user->account_name,
			'account_type'  => $accountType,
			'parent_id'     => $parentId,
			'log_type'      => 'success',
			'log_ip'        => $ip,
			'log_area_text' => $areaText,
			'log_area_name' => $areaName,
			'log_area_id'   => $areaId,
			'log_content'   => '登陆成功',
		]);
	}

}
