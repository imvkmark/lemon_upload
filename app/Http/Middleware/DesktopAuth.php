<?php namespace App\Http\Middleware;

use App\Models\PamAccount;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class DesktopAuth {

	/**
	 * The Guard implementation.
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 * @param  Guard $auth
	 */
	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if ($this->auth->guest()) {
			return site_end('error', '登陆已过期!', 'location|' . route('dsk_home.login'));
		}

		if (\Auth::user()->account_type != PamAccount::ACCOUNT_TYPE_DESKTOP) {
			return site_end('error', '只有管理员才能访问后台， 其他用户类型不可以!', 'location|' . route('dsk_home.login'));
		}

		$routeName = \Route::currentRouteName();
		if ($routeName && !\Rbac::capable($routeName)) {
			return site_end('error', '后台权限不足, 您无权访问本模块!');
		}
		return $next($request);
	}

}
