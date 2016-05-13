<?php
/*
 * 前台路由
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @time       2015/10/7 23:00
 */
Route::group([
	'namespace' => 'Support',
], function () {
	// suffix
	// default : json
	// html string html code
	// validate string true/false


	Route::controller('support_plugin', 'PluginController', [
		'getArea' => 'support_plugin.area',
	]);

	Route::controller('support_validate', 'ValidateController', [
		'postAccountNameAvailable' => 'support_validate.account_name_available',
		'postAccountNameExists'    => 'support_validate.account_name_exists',
		'postMobileCodeValid'      => 'support_validate.mobile_code_valid',
		'postAllowIpAvailable'     => 'support_validate.allow_ip_available',
	]);
});