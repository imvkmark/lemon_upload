<?php namespace App\Http\Controllers\Desktop;

use App\Http\Requests;
use App\Http\Requests\Desktop\PamRoleRequest;
use App\Lemon\Repositories\Application\Rbac\Helper\RbacHelper;
use App\Lemon\Repositories\Sour\LmUtil;
use App\Models\PamRole;
use App\Models\PamAccount;
use App\Models\PamRoleAccount;
use Illuminate\Http\Request;

/**
 * 角色管理
 * Class PamRoleController
 * @package App\Http\Controllers\Desktop
 */
class PamRoleController extends InitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth');
	}

	/**
	 * Display a listing of the resource.
	 * @param Request $request
	 * @return \Response
	 */
	public function getIndex(Request $request) {
		$type  = $request->input('type', PamAccount::ACCOUNT_TYPE_DESKTOP);
		$roles = PamRole::where('account_type', $type)
			->orderBy('created_at', 'desc')
			->paginate($this->pageNum);
		return view('desktop.pam_role.index', [
			'roles' => $roles,
		]);
	}

	public function getCreate() {
		return view('desktop.pam_role.item');
	}

	/**
	 * Store a newly created resource in storage.
	 * @param PamRoleRequest $request
	 * @return \Response
	 */
	public function postCreate(PamRoleRequest $request) {
		$input = $request->all();
		$id    = PamRole::create($input);
		if ($id) {
			return site_end('success', trans('desktop.create_success'), 'location|' . route('dsk_pam_role.index', ['type' => $input['account_type']]));
		} else {
			return site_end('error', trans('desktop.create_error'));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  int $id
	 * @return \Response
	 */
	public function postDestroy($id) {
		$roleName = PamRole::where('id', $id)->value('role_name');
		if ($roleName == 'root') {
			return site_end('error', trans('desktop.role.Super admin can not be deleted'));
		}
		$count = PamRoleAccount::whereRaw('role_id= ? ', [$id])->count();
		if ($count) {
			return site_end('error', trans('desktop.role.delete_has_account'));
		} else {
			$role = PamRole::find($id);
			PamRole::destroy($id);
			return site_end('success', trans('desktop.role.delete_success'), 'location|' . route('dsk_pam_role.index', ['type' => $role['account_type']]));
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  int $id
	 * @return \Response
	 */
	public function getEdit($id) {
		return view('desktop.pam_role.item', [
			'item' => PamRole::findOrFail($id),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 * @param Request $request
	 * @param  int    $id
	 * @return \Response
	 */
	public function update(Request $request, $id) {
		$result      = PamRole::where('role_id', $id)->update($request->except(['_token', '_method']));
		$accountType = PamRole::info($id, 'account_type', false);
		if ($result) {
			return site_end('success', trans('desktop.role.update_success'), 'location|' . route('dsk_pam_role.index', ['type' => $accountType]));
		}
	}


	/**
	 * 验证
	 * @param null|int $id
	 * @internal param Request $request
	 */
	public function postCheck(Request $request, $id = null) {
		$Role = PamRole::where('role_name', $request->input('role_name'));
		if ($id) {
			$Role->where('role_id', '!=', $id);
		}
		$role_id = $Role->value('role_id');
		LmUtil::av($role_id);
	}


	/**
	 * 带单列表
	 * @param $role_id
	 * @return \Illuminate\View\View
	 */
	public function getMenu($role_id) {
		$role        = PamRole::find($role_id);
		$accountType = $role->account_type;
		$permission  = RbacHelper::permission($accountType);
		$perms       = $role->perms();
		if (!$permission) {
			return site_end('error', '暂无权限信息！');
		}
		return view('desktop.pam_role.menu', [
			'permission' => $permission,
			'role'       => $role,
			'perms'      => $perms,
		]);
	}

	/**
	 * 更新会员组配置菜单成功
	 * @param Request $request
	 * @param         $role_id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postMenu(Request $request, $role_id) {
		$role = PamRole::find($role_id);
		$key  = $request->input('key');
		if (!$key) {
			$perms = [];
		} else {
			$perms = array_keys($key);
		}
		$role->savePermissions($perms);
		$role->cachedPermissions();
		return site_end('success', '保存会员权限配置成功!');
	}


}
