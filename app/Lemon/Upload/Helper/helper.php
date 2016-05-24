<?php

/**
 * 获取网站配置
 * @param null $key
 * @return mixed|null
 */
function site($key = null) {
	static $site;
	if (!$site) {
		$site = App\Models\BaseConfig::getCache('site');
	}
	return $key
		? isset($site[$key]) ? $site[$key] : null
		: $site;
}


function js_global($url = '') {
	$url       = $url ?: config('app.url');
	$url_js    = $url . '/assets/js';
	$url_image = $url . '/assets/image';

	$cookie_prefix = config('app.cookie_prefix');
	$cookie_path   = config('app.cookie_path');
	$cookie_domain = \Input::getHost();

	$supportUrl     = [
		'game_server_html' => route('support_game.server_html'),
		'game_type_html'   => route('support_game.type_html'),
	];
	$supportUrlJson = json_encode($supportUrl);

	$uploadUrl = route('upload.image');
	$js        = <<<JS
	define(function(){
		return {
		    cookie_domain : ".{$cookie_domain}",
		    cookie_path : "{$cookie_path}",
		    cookie_prefix : "{$cookie_prefix}",
		    url_site : "{$url}",
		    url_js : "{$url_js}",
		    url_image : "{$url_image}",
		    support_url  :  {$supportUrlJson},
		    upload_url  :  "{$uploadUrl}"
		}
	})
JS;
	return str_replace(["\n", "\/", " ", "\t"], ['', '/', '', ''], $js);
}