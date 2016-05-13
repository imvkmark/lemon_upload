<?php namespace App\Http\Middleware;

use App\Lemon\Dailian\Action\ActionValidate;
use App\Lemon\Repositories\System\SysUrl;
use Closure;
use Illuminate\Http\RedirectResponse;

class FrontValidation {


	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if (\Auth::id()) {
			$Validate = new ActionValidate();
			if (
				$Validate->getValidations(\Auth::id()) // has validation
				&&
				!\Session::has('front_validated') // not validate
			) {
				$route = \Route::currentRouteName();
				SysUrl::setGo(route($route));
				return new RedirectResponse(route('user.validate'));
			}
		}
		return $next($request);
	}

}
