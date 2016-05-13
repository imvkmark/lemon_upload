<?php namespace App\Lemon\Repositories\Providers;


use App\Models\DailianOrder;
use App\Models\PamRole;
use App\Policies\DailianOrderPolicy;
use App\Policies\PamRolePolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class PolicyServiceProvider extends ServiceProvider {

	/**
	 * 应用的策略映射
	 * @var array
	 */
	protected $policies = [
		PamRole::class      => PamRolePolicy::class,
		DailianOrder::class => DailianOrderPolicy::class,
	];

	/**
	 * 注册应用所有的认证/授权服务.
	 * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
	 * @return void
	 */
	public function boot(GateContract $gate) {
		parent::registerPolicies($gate);
	}


}
