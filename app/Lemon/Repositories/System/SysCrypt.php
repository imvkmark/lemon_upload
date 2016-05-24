<?php namespace App\Lemon\Repositories\System;

/**
 * 系统加密
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */

use App\Lemon\Repositories\Sour\LmArr;
use App\Lemon\Repositories\Sour\LmEnv;

/**
 * @package App\Lemon\Project
 */
class SysCrypt {

	/**
	 * 加密
	 * @param        $txt
	 * @param string $key
	 * @return mixed
	 */
	public static function encode($txt, $key = null) {
		if (is_null($key)) {
			$key = config('app.key');
		} else {
			$key = '';
		}
		$rnd = md5(microtime());
		$len = strlen($txt);
		$ren = strlen($rnd);
		$ctr = 0;
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$ctr = $ctr == $ren ? 0 : $ctr;
			$str .= $rnd[$ctr] . ($txt[$i] ^ $rnd[$ctr++]);
		}
		return str_replace('=', '', base64_encode(self::_calc($str, $key)));
	}

	/**
	 * 解密
	 * @param        $txt
	 * @param string $key
	 * @return string
	 */
	public static function decode($txt, $key = null) {
		if (is_null($key)) {
			$key = config('app.key');
		} else {
			$key = '';
		}
		$txt = self::_calc(base64_decode($txt), $key);
		$len = strlen($txt);
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$tmp = $txt[$i];
			$str .= $txt[++$i] ^ $tmp;
		}
		return $str;
	}


	/**
	 * 加密动作, 验证时候使用
	 * 用来验证是否本IP操作
	 *      $action = Crypt::action('action')
	 * @param $action
	 * @return string
	 */
	public static function action($action) {
		$ip  = LmEnv::ip();
		$key = config('app.key');
		return md5(md5($action . $key . $ip));
	}


	/**
	 * 授权生成, 支持多个参数的传入
	 * SysCrypt::auth('abc');
	 * @param $args
	 * @return string
	 */
	public static function auth($args) {
		$arg = is_array($args) ? LmArr::combine($args) : $args;
		$key = config('app.key');
		return md5(md5($arg . $key));
	}


	/**
	 * 检测权限是否正确, 最后传入的是权限验证字串
	 * SysCrypt::checkAuth('abc', '842d1b45f3d47c2aa87fe58d755158c1')
	 * @param $args
	 * @param $auth
	 * @return bool
	 */
	public static function checkAuth($args, $auth) {
		return self::auth($args) === $auth;
	}

	/**
	 * 计算方法
	 * @param $txt
	 * @param $key
	 * @return string
	 */
	private static function _calc($txt, $key) {
		$key = md5($key);
		$len = strlen($txt);
		$ken = strlen($key);
		$ctr = 0;
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$ctr = $ctr == $ken ? 0 : $ctr;
			$str .= $txt[$i] ^ $key[$ctr++];
		}
		return $str;
	}

	/**
	 * 权责加密
	 * @return string
	 */
	public static function copyright() {
		return substr(md5(config('lemon.author') . '-|-' . config('lemon.email') . '-|-' . config('lemon.website')), 0, 7);
	}


	/**
	 * 序列加密
	 * @param array $array
	 * @return string
	 */
	public static function crypt($array = []) {
		if (is_array($array)) {
			ksort($array);
			$array = LmArr::toKvStr($array);
		}
		return sha1(md5($array));
	}
}