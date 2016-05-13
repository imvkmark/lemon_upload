<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Lemon\Repositories\System\SysAcl;
use App\Models\AccountFront;
use App\Models\PamAccount;
use App\Models\PamRole;
use App\Models\PamRoleAccount;
use Illuminate\Http\Request;

/**
 * 初始化文件
 * Class InitController
 * @package App\Http\Controllers\Site
 */
class InitController extends Controller {

	/**
	 * 权限验证的用户对象
	 * @var \App\Models\PamAccount
	 */
	protected $pam = [];

	/**
	 * 用户
	 * @var AccountFront
	 */
	protected $front = [];

	/**
	 * 本用户的角色信息
	 * @var PamRole
	 */
	protected $role = [];


	/**
	 * 是否子账号
	 * @var bool
	 */
	protected $isSub = false;

	/**
	 * 主账号
	 * @var AccountFront
	 */
	protected $owner = null;

	/**
	 * 父账号ID
	 * @var int
	 */
	protected $ownerId = 0;

	/**
	 * 当前登陆账号的ID
	 * @type int
	 */
	protected $accountId = 0;


	/** @type string 当前登录的用户名 */
	protected $accountName = '';


	/** @type int|mixed 本用户的角色 ID */
	protected $roleId = 0;


	public function __construct(Request $request) {
		parent::__construct();

		// 后台授权暂时删除 2016-04-17
		/*
		if (\Session::has('desktop_auth')) {
			$auth = \Session::get('desktop_auth');
			$auth = SysCrypt::decode($auth);
			$auth = LmStr::parseKey($auth);
			if (isset($auth['desktop_id']) && $auth['front_id']) {
				if (PamAccount::info($auth['front_id']) && $auth['desktop_id'] == \Auth::id()) {
					// 检测后台登录用户是当前用户
					\FrontAuth::loginUsingId($auth['front_id']);
				} else {
					\Session::remove('desktop_auth');
				}
			} else {
				\Session::remove('desktop_auth');
			}
		}
		*/
		if (\Auth::check() && \Auth::user()->account_type == PamAccount::ACCOUNT_TYPE_FRONT) {
			$this->pam         = \Auth::user();
			$this->front       = AccountFront::findOrFail(\Auth::id());
			$this->roleId      = PamRoleAccount::getRoleIdByAccountId(\Auth::id());
			$this->role        = PamRole::findOrFail($this->roleId);
			$this->accountId   = $this->front->account_id;
			$this->accountName = $this->pam->account_name;
			// 账号所有者

			if ($this->front->parent_id) {
				$this->isSub   = true;
				$this->ownerId = $this->front->parent_id;
				$this->owner   = AccountFront::find($this->front->parent_id);
			} else {
				$this->ownerId = $this->front->account_id;
				$this->owner   = $this->front;
				$this->isSub   = false;
			}
		}

		\View::share([
			'_pam'          => $this->pam,                // 当前账号的基础资料
			'_front'        => $this->front,              // 当前用户的前端资料
			'_owner'        => $this->owner,              // 所有者的资料, 不包含用户名
			'_owner_id'     => $this->ownerId,            // 所有者的ID
			'_is_sub'       => $this->isSub,              // 是否子账号
			'_role'         => $this->role,               // 当前用户组
			'_role_id'      => $this->roleId,             // 当前用户角色ID
			'_account_id'   => $this->accountId,          // 当前账户ID
			'_account_name' => $this->accountName,        // 当前账户名
			'_avatar'       => $this->accountId ? avatar($this->accountId) : '',  // 当前用户头像
		]);

		// for title
		$frontKv = SysAcl::key(PamAccount::ACCOUNT_TYPE_FRONT, null, true);
		\View::share([
			'_title' => isset($frontKv[$this->route]) ? $frontKv[$this->route]['title'] : '',
		]);
	}

	public function deny($message = '') {
		if (!$message) {
			$message = '您无权访问本页面';
		}
		return view('front.inc.deny', [
			'message' => $message,
		]);
	}

}