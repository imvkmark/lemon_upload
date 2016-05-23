<?php namespace App\Http\Controllers\Desktop;

use App\Http\Requests;
use App\Http\Requests\Desktop\LoginRequest;
use App\Lemon\Repositories\Application\SettingUi;
use App\Lemon\Repositories\Sour\LmFile;
use App\Lemon\Repositories\System\SysAcl;
use App\Models\BaseConfig;
use App\Models\PamAccount;
use App\Models\PluginAllowip;
use Illuminate\Http\Request;


class LemonHomeController extends InitController {


	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth', [
			'except' => [
				'getLogin',
				'postLogin',
				'getTest',
			],
		]);
	}


	/**
	 * 登录
	 * @return $this|\Illuminate\Http\RedirectResponse
	 * @internal param LoginRequest $request
	 */
	public function getLogin() {
		if (\Auth::check() && \Auth::user()->account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
			return site_end('success', trans('desktop.home.login_already'), 'location|' . route('dsk_lemon_home.cp'));
		}
		return view('desktop.home.login');
	}

	public function postLogin(LoginRequest $request) {
		$inputs      = $request->only('adm_name', 'adm_pwd');
		$credentials = [
			'account_type' => PamAccount::ACCOUNT_TYPE_DESKTOP,
			'account_name' => $inputs['adm_name'],
			'password'     => $inputs['adm_pwd'],
		];

		if (\Auth::once($credentials)) {
			if (!\Rbac::hasRole('root')) {
				// check is_enable
				$account = \Auth::user();
				if ($account['is_enable'] == 'N') {
					return site_end('error', '用户被禁用', 'location|' . route('dsk_lemon_home.login'), $request->only('adm_name'));
				}
			}
			\Auth::login(\Auth::user(), true);
			return site_end('success', '登陆成功', 'location|' . route('dsk_lemon_home.cp'));
		} else {
			\Event::fire('auth.failed', [$credentials]);
			return site_end('error', '登陆用户名密码不匹配', 'location|' . route('dsk_lemon_home.login'), $request->only('adm_name'));
		}
	}


	/**
	 * 修改本账户密码
	 * @return \Illuminate\View\View
	 */
	public function getPassword() {
		return view('desktop.home.password');
	}

	/**
	 * 修改本账户密码
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postPassword(Request $request) {

		$validator = \Validator::make($request->all(), [
			'password'     => 'required|confirmed',
			'old_password' => 'required',
		]);
		if ($validator->fails()) {
			return site_end('error', $validator->errors());
		}

		$old_password = $request->input('old_password');
		if ($old_password) {
			if (!PamAccount::checkPassword($this->pam, $old_password)) {
				return site_end('error', '原密码错误!');
			}
		}


		$password = $request->input('password');
		PamAccount::changePassword(\Auth::id(), $password);
		\Auth::logout();
		return site_end('success', trans('desktop.edit_password_ok_and_relogin'), 'location|' . route('dsk_lemon_home.login'));
	}

	/**
	 * 登出
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function getLogout() {
		\Auth::logout();
		return site_end('success', trans('desktop.logout_ok'), 'location|' . route('dsk_lemon_home.login'));
	}

	/**
	 * 控制面板
	 * @return \Illuminate\View\View
	 */
	public function getCp() {
		$menus = SysAcl::menu(PamAccount::ACCOUNT_TYPE_DESKTOP, $this->roleId, true);
		return view('desktop.home.cp', [
			'menus' => $menus,
		]);

	}

	/**
	 * 欢迎页面
	 * @return \Illuminate\View\View
	 */
	public function getWelcome() {
		$build     = LmFile::get(app_path('build.md'));
		$buildHtml = nl2br($build);
		return view('desktop.home.welcome', [
			'html' => $buildHtml,
		]);
	}


	public function getTip() {
		return view('desktop.inc.tip');
	}

	public function getSetting() {
		$Ui = new SettingUi('site');
		$Ui->setDesktop();
		$Ui->setTitle('网站设置');
		$site = site();
		return $Ui->render($site);
	}

	public function postSetting(Request $request) {
		BaseConfig::configUpdate($request->except(['_token']), 'site');
		BaseConfig::reCache();
		return site_end('success', '更新配置成功', 'location|' . route('dsk_site.setting'));
	}

	/**
	 * 更新缓存
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function getCache() {
		BaseConfig::reCache();
		SysAcl::reCache();
		return site_end('success', '更新缓存成功');
	}
}
