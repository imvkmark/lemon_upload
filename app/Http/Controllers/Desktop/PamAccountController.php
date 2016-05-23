<?php namespace App\Http\Controllers\Desktop;

use App\Http\Requests;
use App\Http\Requests\Desktop\PamAccountRequest;
use App\Lemon\Repositories\System\SysCrypt;
use App\Models\AccountDesktop;
use App\Models\AccountDevelop;
use App\Models\AccountFront;
use App\Models\PamAccount;
use App\Models\PamLog;
use App\Models\PamRole;
use App\Models\PamRoleAccount;
use Illuminate\Http\Request;

/**
 * 账户管理
 * Class GameController
 * @package App\Http\Controllers\Desktop
 */
class PamAccountController extends InitController {

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
		$account_type = $request->input('type');
		$search       = $request->input('search');

		$tb_pam = (new PamAccount())->getTable();
		$tb_ra  = (new PamRoleAccount())->getTable();

		$Db = \DB::table($tb_pam)->where('account_type', $account_type);
		$Db->join($tb_ra, $tb_pam . '.account_id', '=', $tb_ra . '.account_id'); // role
		if ($search['role_id']) {
			$Db->where($tb_ra . '.role_id', $search['role_id']);
		}
		if ($search['account_name']) {
			$Db->where($tb_pam . '.account_name', 'like', '%' . $search['account_name'] . '%');
		}
		if ($account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
			$tb_dsk = (new AccountDesktop())->getTable();
			$Db->join($tb_dsk, $tb_pam . '.account_id', '=', $tb_dsk . '.account_id');
		} elseif ($account_type == PamAccount::ACCOUNT_TYPE_DEVELOP) {
			$tb_ft = (new AccountDevelop())->getTable();
			$Db->join($tb_ft, $tb_pam . '.account_id', '=', $tb_ft . '.account_id');
		} else {
			$tb_ft = (new AccountFront())->getTable();
			$Db->join($tb_ft, $tb_pam . '.account_id', '=', $tb_ft . '.account_id');
		}

		$accounts = $Db->paginate($this->pageNum);
		$accounts->appends($request->input());
		$roles = PamRole::getLinear($account_type);
		return view('desktop.account.index', [
			'accounts'     => $accounts,
			'account_type' => $account_type,
			'search'       => ['search' => $search],
			'roles'        => $roles,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 * @param Request $request
	 * @return \Response
	 */
	public function getCreate(Request $request) {
		$account_type = $request->input('type');
		return view('desktop.account.item', [
			'account_type' => $account_type,
			'roles'        => PamRole::getLinear($account_type),
		]);
	}

	/**
	 * 存储
	 * @param PamAccountRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postCreate(PamAccountRequest $request) {
		$account_name = $request->input('account_name');
		$password     = $request->input('password');
		$account_type = $request->input('account_type');
		$role_id      = $request->input('role_id');
		$account_id   = PamAccount::register($account_name, $password, $account_type, $role_id);
		if ($account_id) {
			if ($account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
				$desktop               = $request->input('desktop');
				$desktop['account_id'] = $account_id;
				AccountDesktop::create($desktop);
			}
			if ($account_type == PamAccount::ACCOUNT_TYPE_FRONT) {
				$front               = $request->input('front');
				$front['account_id'] = $account_id;
				AccountFront::create($front);
			}
			if ($account_type == PamAccount::ACCOUNT_TYPE_DEVELOP) {
				$develop               = $request->input('develop');
				$develop['account_id'] = $account_id;
				AccountDevelop::create($develop);
			}
			return site_end('success', '用户添加成功', 'location|' . route('dsk_account.index', ['type' => $account_type]));
		} else {
			return site_end('error', '用户添加失败');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 * @param Request $request
	 * @return \Response
	 */
	public function postDestroy(Request $request) {
		$id      = $request->input('id');
		$account = PamAccount::find($id);

		// todo 检测用户订单(不可删)
		// todo 检测用户金钱记录(不可删)

		\DB::transaction(function () use ($account) {
			// 删除 pam

			$id           = (int) $account['account_id'];
			$account_type = $account['account_type'];
			PamAccount::destroy($id);

			// 删除 pam 附属资料
			if ($account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
				AccountDesktop::destroy($id);
			}
			if ($account_type == PamAccount::ACCOUNT_TYPE_FRONT) {
				AccountFront::destroy($id);
			}
			if ($account_type == PamAccount::ACCOUNT_TYPE_DEVELOP) {
				AccountDevelop::destroy($id);
			}

			// 删除 role_account 关联
			PamRoleAccount::where('account_id', $id)->delete();
		});

		return site_end('success', '删除用户成功', 'location|' . route('dsk_account.index', ['type' => $account['account_type']]));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  int $id
	 * @return \Response
	 */
	public function getEdit($id) {
		$item = PamAccount::info($id, true);
		return view('desktop.account.item', [
			'account_type' => $item['account_type'],
			'item'         => $item,
			'roles'        => PamRole::getLinear($item['account_type']),
		]);
	}

	public function getAuth($id) {
		$user = PamAccount::info($id);
		if ($user['account_type'] != PamAccount::ACCOUNT_TYPE_FRONT) {
			return site_end('error', '用户类型不正确!');
		}

		// set cookie
		\Session::set('desktop_auth', SysCrypt::encode('desktop_id|' . \Auth::id() . ';front_id|' . $id));

		// show and redirect
		return site_end('success', '用户授权成功!', [
			'location' => route('home.cp'),
			'time'     => '3000',
		]);
	}

	/**
	 * 更新
	 * @param Request $request
	 * @param  int    $id
	 * @return \Response
	 */
	public function postEdit(Request $request, $id) {
		$pam = PamAccount::find($id);

		// 修改密码
		$password = $request->input('password');
		if ($password) {
			PamAccount::changePassword($id, $password);
		}

		// 更新附属信息
		$account_type = $pam['account_type'];
		if ($account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
			AccountDesktop::where('account_id', $id)->update($request->input('desktop'));
		}
		if ($account_type == PamAccount::ACCOUNT_TYPE_FRONT) {
			$front = $request->input('front');
			if (isset($front['payword']) && $front['payword']) {
				AccountFront::changePayword($id, $front['payword']);
			}
			unset($front['payword'], $front['payword_confirmation']);
			AccountFront::where('account_id', $id)->update($front);
		}
		if ($account_type == PamAccount::ACCOUNT_TYPE_DEVELOP) {
			AccountDevelop::where('account_id', $id)->update($request->input('develop'));
		}

		// 更新角色id
		$role_id = $request->input('role_id');
		PamRoleAccount::where('account_id', $id)->update([
			'role_id' => $role_id,
		]);

		$account_type = $request->input('account_type');

		return site_end('success', '用户资料编辑成功', 'location|' . route('dsk_account.index', ['type' => $account_type]));
	}

	/**
	 * 改变账户状态
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postStatus(Request $request) {
		$field   = $request->input('field');
		$status  = $request->input('status');
		$id      = $request->input('id');
		$user = PamAccount::find($id);
		if ($user->hasRole('root')) {
			return site_end('error', '账号是超级管理员', 'reload|1');
		}
		PamAccount::where('account_id', $id)->update([
			$field => $status,
		]);
		return site_end('success', '状态修改成功', 'reload|1');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getLog() {
		$items = PamLog::orderBy('created_at', 'desc')->paginate($this->pageNum);
		return view('desktop.account.log', [
			'items' => $items,
		]);
	}
}
