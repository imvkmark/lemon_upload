<?php
return [

	/*
	|--------------------------------------------------------------------------
	| 存储的磁盘
	|--------------------------------------------------------------------------
	| 定义在 filesystem.php 中 disks 部分, 默认是 public
	*/
	'server_disk'        => env('UPLOAD_SERVER_DISK', 'public_upload'),


	/*
	|--------------------------------------------------------------------------
	| 服务器令牌失效时间 (分钟)
	|--------------------------------------------------------------------------
	*/
	'expires' => 3600,
	
	/*
	|--------------------------------------------------------------------------
	| 替换URL地址
	|--------------------------------------------------------------------------
	*/
	'replace_url'        => [
		'http://www.larxd.com/uploads/',
		'http://img1.ixdcw.com/',
		'http://img2.ixdcw.com/thumber/config/',
		'http://img1.ixdcw.net/',
		'http://img.ixdcw.net/xundu/default/',
		'http://img.ixdcw.com/xundu/default/',
		'http://img.mirror1.larxd.com/',
		'http://img1.www.ixdcw.net/',
		'http://www.lar_xd.com',
	],
];