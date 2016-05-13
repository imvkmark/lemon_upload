<?php namespace App\Http\Controllers\Support;

use App\Lemon\Dailian\Action\ActionValidate;
use App\Models\GameName;
use App\Models\GameSource;
use App\Models\PamAccount;
use App\Models\PluginAllowip;
use Illuminate\Http\Request;
use League\Flysystem\Util;

/**
 * 用于 js 验证
 * Class ValidateController
 * @package App\Http\Controllers\Support
 */
class ValidateController extends InitController {

	public function __construct(Request $request) {
		parent::__construct($request);
	}


	/**
	 * account_name
	 * @param Request $request
	 */
	public function postAccountNameAvailable(Request $request) {
		$account_name = $request->input('account_name');
		if (PamAccount::accountNameExists($account_name)) {
			exit('false');
		} else {
			exit('true');
		}
	}

	/**
	 * 账户存在
	 * @param Request $request
	 */
	public function postAccountNameExists(Request $request) {
		$account_name = $request->input('account_name');
		if (PamAccount::accountNameExists($account_name)) {
			exit('true');
		} else {
			exit('false');
		}
	}

	/**
	 * 验证码可用
	 * mobile
	 * mobile_captcha
	 * @param Request $request
	 */
	public function postMobileCodeValid(Request $request) {
		$mobile_code = $request->input('mobile_captcha');
		$account_id  = $request->input('account_id');
		$subject     = $request->input('mobile');
		$Validate    = new ActionValidate();
		if ($Validate->checkMobileCodeValid($subject, $mobile_code, $account_id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}


	/**
	 * 检查是否存在游戏名称
	 * @param Request $request
	 * @param null    $id
	 */
	public function postGameNameAvailable(Request $request, $id = null) {
		$GameName = GameName::where('game_name', $request->input('game_name'));
		if ($id) {
			$GameName->where('game_id', '!=', $id);
		}
		if ($GameName->exists()) {
			exit('false');
		} else {
			exit('true');
		}
	}

	/**
	 * 检查是否存在ip
	 * @param Request $request
	 */
	public function postAllowIpAvailable(Request $request) {
		$Ip = PluginAllowip::where('ip_addr', $request->input('ip_addr'));
		if ($Ip->exists()) {
			exit('false');
		} else {
			exit('true');
		}
	}

	/**
	 * 检查是否存在游戏来源
	 * @param Request $request
	 * @param null    $id
	 */
	public function postGameSourceAvailable(Request $request, $id = null) {
		$id         = $request->input('id');
		$GameSource = GameSource::where('source_name', $request->input('source_name'));
		if ($id) {
			$GameSource->where('source_id', '!=', $id);
		}
		if ($GameSource->exists()) {
			exit('false');
		} else {
			exit('true');
		}
	}

}
