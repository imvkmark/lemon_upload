<?php namespace App\Lemon\Repositories\Application\Rbac\Contracts;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 * @license MIT
 * @package Zizaco\Entrust
 */

interface RbacPermissionInterface {

	/**
	 * Many-to-Many relations with role model.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles();
}
