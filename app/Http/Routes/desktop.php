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
		'as'   => 'dsk_home.cp',
		'uses' => 'HomeController@getCp',
	]);

	// 主页
	Route::controller('dsk_home', 'HomeController', [
		'getWelcome'   => 'dsk_home.welcome',
		'getCp'        => 'dsk_home.cp',
		'getLogout'    => 'dsk_home.logout',
		'getPassword'  => 'dsk_home.password',
		'postPassword' => 'dsk_home.password',
		'getLogin'     => 'dsk_home.login',
		'postLogin'    => 'dsk_home.login',
	]);

	// 网站设置
	Route::controller('dsk_site', 'SiteController', [
		'postSetting' => 'dsk_site.setting',
		'getCache'    => 'dsk_site.cache',
	]);

	// 角色管理
	Route::controller('dsk_pam_role', 'PamRoleController', [
		'getIndex'    => 'dsk_pam_role.index',
		'getCreate'   => 'dsk_pam_role.create',
		'postCreate'  => 'dsk_pam_role.create',
		'postCheck'   => 'dsk_pam_role.check',
		'getMenu'     => 'dsk_pam_role.menu',
		'postMenu'    => 'dsk_pam_role.menu',
		'getEdit'     => 'dsk_pam_role.edit',
		'postDestroy' => 'dsk_pam_role.destroy',
	]);

	// 账户列表
	Route::controller('dsk_account', 'AccountController', [
		'postStatus'               => 'dsk_account.status',
		'getLog'                   => 'dsk_account.log',
		'getEdit'                  => 'dsk_account.edit',
		'postEdit'                 => 'dsk_account.edit',
		'postDestroy'              => 'dsk_account.destroy',
		'getCreate'                => 'dsk_account.create',
		'postCreate'               => 'dsk_account.create',
		'getIndex'                 => 'dsk_account.index',
		'getAuth'                  => 'dsk_account.auth',
	]);


});