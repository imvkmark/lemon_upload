<?php
return [
	// common
	'create_success'  => '创建成功',
	'create_error'    => '创建失败',
	'edit_success'    => '编辑成功',
	'destroy_success' => '删除成功',

	'home' => [
		'login_already'   => '您已登录',
		'login_ip_banned' => '您的IP :ip 不在允许访问之列!请不要尝试登陆!!!',
	],

	'edit_password'                  => '修改密码',
	'account_name'                   => '账户名',
	'password'                       => '密 码',
	'password_confirmation'          => '重复密码',
	'logout_ok'                      => '您已安全退出登陆',
	'login_ok'                       => '您已成功登陆',
	'login_expired'                  => '登陆已过期',
	'edit_password_ok_and_relogin'   => '修改密码成功, 请重新登陆',
	'no_permission_to_visit_desktop' => '您无权访问此功能',

	// 后台登陆
	'login'                          => [
		'username'         => '用户名',
		'usernameRequired' => '请输入管理员账号',
		'password'         => '密码',
		'passwordRequired' => '请输入管理员密码',
		'captcha'          => '验证码',
		'already_login'    => '您已登录',
		'ip_banned'        => '您的IP :ip 不在允许访问之列!请不要尝试登陆!',

	],
	// frame 框架
	'cp'                             => [
		'hello'    => '您好',
		'location' => '您的位置',
		'welcome'  => '系统首页',
		'logout'   => '退出登陆',
	],
	'game_name'                      => [
		'name'             => '游戏名称',
		'store_success'    => '添加游戏成功',
		'store_failed'     => '添加游戏失败',
		'delete_has_order' => '订单中存在该游戏, 该游戏不能删除',
		'delete_success'   => '游戏删除成功',
		'update_success'   => '编辑资料成功',
	],
	'site'                           => [
		'config_update_success' => '更新配置成功',
	],
	'account'                        => [
		'account_name' => '用户名',
	],
	'role'                           => [
		'Super admin can not be deleted' => '超级管理员不能删除',
		'delete_has_account'             => '本角色下有用户, 请先删除用户后再删除本会员组!',
		'delete_success'                 => '成功删除角色!',
		'update_success'                 => '成功修改角色资料!',
	],
	'ip'                             => [
		'ip' => 'IP',
	],
];