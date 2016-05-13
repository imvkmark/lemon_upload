<?php namespace App\Http\Controllers\Desktop;


use App\Lemon\Repositories\System\SysAcl;
use App\Models\PamAccount;
use App\Models\PamRoleAccount;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AccountDesktop;
use App\Models\PamRole;

/**
 * 管理员初始化文件
 * Class InitController
 * @package App\Http\Controllers\Desktop
 */
class InitController extends Controller {

	/**
	 * 权限验证的用户对象
	 * @var PamAccount
	 */
	protected $pam = [];

	/**
	 * 管理员附属数据
	 * @var AccountDesktop
	 */
	protected $admin = [];

	/**
	 * 本用户的角色信息
	 * @var array
	 */
	protected $role = [];


	/**
	 * 本用户的角色 ID
	 * @var int
	 */
	protected $roleId = 0;

	public function __construct(Request $request) {
		parent::__construct();

		if (\Auth::check() && \Auth::user()->account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
			$this->pam    = \Auth::user();
			$this->admin  = AccountDesktop::findOrFail($this->pam->account_id);
			$this->roleId = PamRoleAccount::getRoleIdByAccountId($this->pam->account_id);
			$this->role   = PamRole::findOrFail($this->roleId);
		}

		\View::share([
			'_pam'          => $this->pam,
			'_admin'        => $this->admin,
			'_role'         => $this->role,
			'_role_id'      => $this->roleId,
			'_account_id'   => $this->pam ? $this->pam->account_id : 0,
			'_account_name' => $this->pam ? $this->pam->account_name : '',
			'_pam_types'    => PamAccount::accountTypeAll(),
		]);
		$kv           = SysAcl::key(PamAccount::ACCOUNT_TYPE_DESKTOP, null, true);
		$currentRoute = \Route::currentRouteName();
		\View::share([
			'_title' => isset($kv[$currentRoute]) ? $kv[$currentRoute]['title'] : '',
		]);
	}


	/**
	 * 操作失败/正确的提示
	 * @param string $message
	 * @param string $type
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function tip($message = '操作成功！', $type = 'success') {
		if ($this->route == 'home.tip') {
			exit('This method not allowed to used here!');
		}
		return $this->end($type, $message, 'location|' . route('home.tip'));
	}
}