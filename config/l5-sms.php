<?php
return [
	/*
	|--------------------------------------------------------------------------
	| 短信发送接口
	|--------------------------------------------------------------------------
	: ihuyi : 互亿无线   www.ihuyi.com
	: log   : 保存到本地日志, 不是真实发送
	|
	*/
	'api_type' => env('SMS_TYPE', 'log'),


	'sms' => [

		'log' => [
			'sign' => '【酸柠檬】',
		],

		'ihuyi' => [
			'public_key' => env('SMS_IHUYI_ACCOUNT'),
			'password'   => env('SMS_IHUYI_PASSWORD'),
			'sign'       => env('SMS_SIGN'),
		],

		'jianzhou' => [
			'public_key' => 'your-key',
			'password'   => 'your-password',
			'sign'       => '【your-sign】',
		],

	],
];