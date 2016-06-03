<?php
Route::get('thumber/{filename}', [
	'as'   => 'vendor_thumber.index',
	'uses' => 'Imvkmark\L5Thumber\Http\ImageController@getIndex',
])->where(['filename' => '[ \w\\.\\/\\-\\@,_]+']);