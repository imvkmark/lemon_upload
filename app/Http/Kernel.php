<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Imvkmark\L5Rbac\Middleware\L5RbacAbility;
use Imvkmark\L5Rbac\Middleware\L5RbacPermission;
use Imvkmark\L5Rbac\Middleware\L5RbacRole;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
		'Clockwork\Support\Laravel\ClockworkMiddleware',
	];

	/**
	 * The application's route middleware.
	 * @var array
	 */
	protected $routeMiddleware = [
		'lm_desktop.auth'     => 'App\Http\Middleware\DesktopAuth',
		'lm_front.auth'       => 'App\Http\Middleware\FrontAuth',
		'lm_front.validation' => 'App\Http\Middleware\FrontValidation',
		'lm_develop.auth'     => 'App\Http\Middleware\DevelopAuth',
		'lm_api.auth'         => 'App\Http\Middleware\ApiAuth',
		'lm_api.access_token' => 'App\Http\Middleware\ApiAccessToken',
		'role'                => L5RbacRole::class,
		'permission'          => L5RbacPermission::class,
		'ability'             => L5RbacAbility::class,
	];

}
