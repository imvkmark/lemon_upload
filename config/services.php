<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses'       => [
		'key'    => '',
		'secret' => '',
		'region' => 'us-east-1',
	],
	'stripe'    => [
		'model'  => 'User',
		'secret' => '',
	],
	'sendcloud' => [
		'api_user'     => '',
		'api_key'      => '',
		'from_address' => '',
		'from_name'    => '',
	],
	'qq'        => [
		'client_id'     => env('QQ_KEY'),
		'client_secret' => env('QQ_SECRET'),
		'redirect'      => env('URL_SITE') . '/' . env('QQ_REDIRECT_URI'),
	],
];
