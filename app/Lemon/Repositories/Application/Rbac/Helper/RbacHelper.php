<?php namespace App\Lemon\Repositories\Application\Rbac\Helper;

use App\Models\PamPermission;
use Illuminate\Database\Eloquent\Collection;

class RbacHelper {

	/**
	 * 获取权限以及分组
	 * @param $account_type
	 * @return static
	 */
	public static function permission($account_type) {
		$permission = PamPermission::where('account_type', $account_type)->get();
		$collection = new Collection($permission);
		return $collection->groupBy('permission_group');
	}

}//
