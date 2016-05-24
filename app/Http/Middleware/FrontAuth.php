<?php namespace App\Http\Middleware;

use App\Models\PamAccount;
use App\Models\PamRoleAccount;
use Closure;

class FrontAuth {

	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if (\Auth::guest()) {
			return site_end('error', '尚未登陆, 请登陆后再行访问!', 'location|' . route('user.login'));
		}
		if (\Auth::user()->account_type != PamAccount::ACCOUNT_TYPE_FRONT) {
			return site_end('error', '只有用户才能访问前台， 其他用户类型不可以!', 'location|' . route('user.login'));
		}
		return $next($request);
	}

}
