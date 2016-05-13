<?php namespace App\Lemon\Repositories\Application\Rbac\Traits;
/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 * @license MIT
 * @package Zizaco\Entrust
 */

use Illuminate\Support\Facades\Config;

trait RbacPermissionTrait {

	/**
	 * Many-to-Many relations with role model.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles() {
		return $this->belongsToMany(Config::get('l5-rbac.role'), Config::get('l5-rbac.permission_role_table'));
	}

	/**
	 * Boot the permission model
	 * Attach event listener to remove the many-to-many records when trying to delete
	 * Will NOT delete any records if the permission model uses soft deletes.
	 * @return void|bool
	 */
	public static function boot() {
		parent::boot();

		static::deleting(function ($permission) {
			if (!method_exists(Config::get('l5-rbac.permission'), 'bootSoftDeletes')) {
				$permission->roles()->sync([]);
			}

			return true;
		});
	}
}
