<?php
/*
 * 前台路由
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @time       2015/10/7 23:00
 */
Route::post('upload_image', [
	'as'   => 'vendor.upload_image',
	'uses' => '\Imvkmark\SlUpload\Http\Controllers\SlUploadController@postImage',
]);