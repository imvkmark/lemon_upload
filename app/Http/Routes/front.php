<?php
/*
 * 前台路由
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @time       2015/10/7 23:00
 */
Route::group([
	'namespace' => 'Front',
], function () {

	// home
	Route::get('test', 'HomeController@getTest');
	Route::get('/', [
		'as'   => 'home.homepage',
		'uses' => 'HomeController@getHomepage',
	]);
	Route::get('cp', [
		'as'   => 'home.cp',
		'uses' => 'HomeController@getCp',
	]);
	Route::controller('home', 'HomeController', [
		'getTest' => 'home.test',
		'getCp'   => 'home.cp',
	]);

});