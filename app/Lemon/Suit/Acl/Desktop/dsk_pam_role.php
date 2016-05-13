<?php
return [
	'title'     => '角色管理',
	'route'     => 'dsk_pam_role',
	'operation' => [
		'index'   => [
			'title' => '用户角色',
			'menu'  => true,
		],
		'create'  => [
			'title' => '创建角色',
			'menu'  => false,
		],
		'edit'    => [
			'title' => '编辑角色',
			'menu'  => false,
		],
		'destroy' => [
			'title' => '删除角色',
			'menu'  => false,
		],
		'menu'    => [
			'title' => '授权登录',
			'menu'  => false,
		],
	],
];