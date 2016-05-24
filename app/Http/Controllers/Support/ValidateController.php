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
	
}
