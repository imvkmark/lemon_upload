<?php namespace App\Models;

use App\Lemon\Repositories\Application\Rbac\Contracts\RbacRoleInterface;
use App\Lemon\Repositories\Application\Rbac\Traits\RbacRoleTrait;
use App\Lemon\Repositories\Sour\LmArr;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 * @license MIT
 * @property integer                                                                  $id
 * @property string                                                                   $role_name
 * @property string                                                                   $role_title
 * @property string                                                                   $role_description
 * @property string                                                                   $account_type
 * @property boolean                                                                  $is_system
 * @property \Carbon\Carbon                                                           $created_at
 * @property \Carbon\Carbon                                                           $updated_at
 */
class PamRole extends \Eloquent implements RbacRoleInterface {

	use RbacRoleTrait;

	protected $table = 'pam_role';

	protected $fillable = [
		'role_name',
		'role_title',
		'role_description',
		'account_type',
	];


	/**
	 * 通过角色来获取账户类型, 由于角色在单条处理中不会存在变化, 故而可以进行静态缓存
	 * @param $role_id
	 * @return mixed
	 */
	public static function getAccountTypeByRoleId($role_id) {
		static $_cache;
		if (!isset($_cache[$role_id])) {
			$_cache[$role_id] = self::where('role_id', $role_id)->value('account_type');
		}
		return $_cache[$role_id];
	}


	/**
	 * 返回一维的角色对应
	 * @param null $accountType
	 * @return array
	 */
	public static function getLinear($accountType = null) {
		$roles  = self::getAll($accountType);
		$linear = [];
		foreach ($roles as $roleId => $role) {
			$linear[$roleId] = self::info($roleId, 'role_name');
		}
		return $linear;
	}

	/**
	 * 根据账户类型获取角色
	 * @param string|null $accountType
	 * @return array
	 */
	public static function getAll($accountType = null, $cache = true) {
		static $roles = null;
		if (empty($roles) || !$cache) {
			if ($accountType) {
				$items = self::where('account_type', $accountType)->get()->toArray();
			} else {
				$items = self::all()->toArray();
			}
			$roles = LmArr::pluck($items, 'role_id');
		}
		return $roles;
	}


	/**
	 * 获取角色信息
	 * @param      $role_id
	 * @param null $key
	 * @return null
	 */
	public static function info($role_id, $key = null, $cache = true) {
		$roles = self::getAll(null, $cache);
		return $key
			? (isset($roles[$role_id][$key]) ? $roles[$role_id][$key] : null)
			: $roles[$role_id];
	}


	
}
