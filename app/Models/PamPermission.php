<?php namespace App\Models;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 * @license MIT
 * @package Zizaco\Entrust
 */


use App\Lemon\Repositories\Application\Rbac\Contracts\RbacPermissionInterface;
use App\Lemon\Repositories\Application\Rbac\Traits\RbacPermissionTrait;

class PamPermission extends \Eloquent implements RbacPermissionInterface {

	use RbacPermissionTrait;

	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'pam_permission';

	protected $fillable = [
		'permission_name',
		'permission_title',
		'permission_description',
		'permission_group',
		'account_type',
		'is_menu',
	];


}
