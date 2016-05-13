<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * The URIs that should be excluded from CSRF verification.
	 * @var array
	 */
	protected $except = [
		'finance/charge-notify',             // 充值回调
		'dsk_validate/*',                    // 后台验证
		'validate/*',                        // 前台验证, 不需要
		'support_validate/*',                // 通用验证, 不需要
		'callback/*',                        // 回调
		'upload_image',                      // 上传图片
		'user/avatar-upload',                // 上传头像
	];

	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		return parent::handle($request, $next);
	}

}
