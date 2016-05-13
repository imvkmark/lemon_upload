<?php namespace App\Lemon\Repositories\System;

/**
 * Cookie常用操作
 * Class Cookie
 */
class SysCookie {


	/**
	 * 判断Cookie是否存在
	 * @param $name
	 * @return bool
	 */
	public static function has($name) {
		return isset($_COOKIE[config('app.cookie_prefix') . $name]);
	}

	/**
	 * 获取某个Cookie值
	 * @param $name
	 * @return string
	 */
	public static function get($name) {
		$value = isset($_COOKIE[config('app.cookie_prefix') . $name]) ? $_COOKIE[config('app.cookie_prefix') . $name] : '';
		if (config('app.cookie_base64')) {
			$value = base64_decode(unserialize($value));
		}
		return $value;
	}

	// 设置某个Cookie值
	public static function set($name, $value, $expire = 0, $path = '', $domain = '') {
		if (empty($path)) {
			$path = config('app.cookie_path');
		}
		if (empty($domain)) {
			$domain = config('app.cookie_domain');
		}

		$expire = !empty($expire) ? time() + $expire : 0;
		if (config('app.cookie_base64')) {
			$value = base64_encode(serialize($value));
		}
		return setcookie(config('app.cookie_prefix') . $name, $value, $expire, $path, $domain);
	}

	/**
	 * 删除某个Cookie值
	 * @param $name
	 */
	public static function remove($name) {
		self::set($name, '', time() - 3600);
		unset($_COOKIE[config('app.cookie_prefix') . $name]);
	}


	/**
	 * 清空所有Cookie值
	 */
	public static function clear() {
		unset($_COOKIE);
	}
}