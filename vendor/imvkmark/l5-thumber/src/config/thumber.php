<?php
return [
	'thumber' => [
		//0: redirect to error png | 1: redirect to error png with error url msg | 2: throw an exception
		'debug'                => 0,
		'source_path'          => '/media/web/www/larxd/public/uploads',
		'system_file_encoding' => 'UTF-8',
		'zip_file_encoding'    => 'GB2312',
		'thumb_cache_path'     => '/media/web/www/larxd/public/uploads',
		'system_cache_path'    => null,
		// GD | Imagick | Gmagick
		'adapter'              => 'GD',

		// 如果存在前缀则不能用缓存重写, if no prefix, will use array key
		'cache'                => 1,
		'error_url'            => 'http://thumb.ixdcw.com/error.png',
		'allow_stretch'        => false,
		//'min_width' => 10,
		//'min_height' => 10,
		'max_width'            => 2000,
		'max_height'           => 2000,
		'quality'              => 100,
		'blending_layer'       => __DIR__ . '/upload/blend.png',
		'redirect_referer'     => true,
		'face_detect'          => [
			'enable'      => 0,
			'draw_border' => 1,
			'cascade'     => __DIR__ . '/data/haarcascades/haarcascade_frontalface_alt.xml',
			'bin'         => __DIR__ . '/bin/opencv.py',
		],
		'png_optimize'         => [
			'enable'  => 0,
			'adapter' => 'pngout',
			'pngout'  => [
				'bin' => __DIR__ . '/bin/pngout.exe',
			],
		],
		'allow_extensions'     => [],
		'allow_sizes'          => [
			//Suggest keep empty here to be overwrite
			//'200*100',
			//'100*100',
		],
		'disable_operates'     => [
			//Suggest keep empty here to be overwrite
			//'filter',
			//'crop',
			//'dummy',
		],
		'watermark'            => [
			'enable'         => 0,
			//position could be tl:TOP LEFT | tr: TOP RIGHT | bl | BOTTOM LEFT | br BOTTOM RIGHT | center
			'position'       => 'br',
			'text'           => '@AlloVince',
			'layer_file'     => __DIR__ . '/layers/watermark.png',
			'font_file'      => __DIR__ . '/layers/Yahei_Mono.ttf',
			'font_size'      => 12,
			'font_color'     => '#FFFFFF',
			'qr_code'        => 0,
			'qr_code_size'   => 3,
			'qr_code_margin' => 4,
		],
		// disable dynamic url
		'dynamicUrlDisabled'   => false,
		// separator of class in url
		'class_separator'      => '!',
		'classes'              => [
			'cover' => 'w_120,h_200',
		],
	],
];