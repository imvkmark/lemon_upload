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

	Route::post('upload_image', [
		'as'   => 'upload.image',
		'uses' => 'UploadController@postImage',
	]);
});