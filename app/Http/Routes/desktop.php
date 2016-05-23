<?php
/*
 * 前台路由
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @time       2015/10/7 23:00
 */

Route::group([
	'namespace' => 'Desktop',
], function () {


	Route::get('dsk_cp', [
		'as'   => 'dsk_lemon_home.cp',
		'uses' => 'LemonHomeController@getCp',
	]);

	// 主页
	Route::controller('dsk_lemon_home', 'LemonHomeController', [
		'getWelcome'  => 'dsk_lemon_home.welcome',
		'getCp'       => 'dsk_lemon_home.cp',
		'getLogout'   => 'dsk_lemon_home.logout',
		'getPassword' => 'dsk_lemon_home.password',
		'getLogin'    => 'dsk_lemon_home.login',
		'getSetting'  => 'dsk_lemon_home.setting',
		'getCache'    => 'dsk_lemon_home.cache',
	]);

	// 角色管理
	Route::controller('dsk_pam_role', 'PamRoleController', [
		'getIndex'    => 'dsk_pam_role.index',
		'getCreate'   => 'dsk_pam_role.create',
		'postCheck'   => 'dsk_pam_role.check',
		'getMenu'     => 'dsk_pam_role.menu',
		'getEdit'     => 'dsk_pam_role.edit',
		'postDestroy' => 'dsk_pam_role.destroy',
	]);

	// 账户列表
	Route::controller('dsk_pam_account', 'PamAccountController', [
		'postStatus'  => 'dsk_pam_account.status',
		'getLog'      => 'dsk_pam_account.log',
		'getEdit'     => 'dsk_pam_account.edit',
		'postDestroy' => 'dsk_pam_account.destroy',
		'getCreate'   => 'dsk_pam_account.create',
		'getIndex'    => 'dsk_pam_account.index',
		'getAuth'     => 'dsk_pam_account.auth',
	]);


});