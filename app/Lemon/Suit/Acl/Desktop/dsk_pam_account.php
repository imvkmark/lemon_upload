<?php
return [
	'title'     => '账号管理',
	'route'     => 'dsk_pam_account',
	'operation' => [
		'index'           => [
			'title' => '用户列表',
			'menu' => true,
		],
		'log'             => [
			'title' => '登陆日志',
			'menu' => true,
		],
		'status'          => [
			'title' => '启用/禁用账号',
			'menu' => false,
		],
		'create'          => [
			'title' => '创建账号',
			'menu' => false,
		],
		'edit'            => [
			'title' => '更改用户资料',
			'menu' => false,
		],
		'destroy'         => [
			'title' => '删除用户',
			'menu' => false,
		],
	]
];