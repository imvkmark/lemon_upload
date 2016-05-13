<?php namespace App\Http\Controllers\Desktop;

use App\Http\Requests;
use App\Models\AccountFront;
use App\Models\GameName;
use App\Models\PamAccount;
use App\Models\PluginAllowip;
use Illuminate\Http\Request;

/**
 * 账户管理
 * Class GameController
 * @package App\Http\Controllers\Desktop
 */
class ValidateController extends InitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth');
	}

	/**
	 * 实名认证
	 * @param Request $request
	 * @return \Illuminate\View\View
	 */
	public function truename(Request $request) {
		$search = $request->input('search');

		$tb_pam = (new PamAccount())->getTable();
		$tb_ft  = (new AccountFront())->getTable();
		$Db     = \DB::table($tb_pam)->where('account_type', 'front');
		$Db->join($tb_ft, $tb_pam . '.account_id', '=', $tb_ft . '.account_id');
		if ($search['account_name']) {
			$Db->where($tb_pam . '.account_name', 'like', '%' . $search['account_name'] . '%');
		}

		$accounts = $Db->paginate($this->pageNum);
		$accounts->appends($request->input());
		return view('desktop.validate.truename', [
			'accounts' => $accounts,
			'search'   => ['search' => $search],
		]);
	}

	public function getValidate(Request $request) {
		$status = $request->input('status');
		$field  = $request->input('field');
		$id     = $request->input('id');
		if (!in_array($field, ['v_email', 'v_mobile', 'v_truename'])) {
			return site_end('error', '没有此项认证内容');
		}
		if (!in_array($status, ['Y', 'N'])) {
			return site_end('error', '认证状态错误');
		}

		AccountFront::where('account_id', $id)->update([
			$field => $status,
		]);
		return site_end('success', '认证状态已变更');
	}

	
}
