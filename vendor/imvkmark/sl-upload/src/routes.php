<?php
Route::controller('dsk_image_key', 'Imvkmark\SlUpload\Http\Controllers\Desktop\SlUploadKeyController', [
	'getIndex'    => 'dsk_image_key.index',
	'getCreate'   => 'dsk_image_key.create',
	'postCreate'  => 'dsk_image_key.create',
	'getEdit'     => 'dsk_image_key.edit',
	'postEdit'    => 'dsk_image_key.edit',
	'postDestroy' => 'dsk_image_key.destroy',
]);
