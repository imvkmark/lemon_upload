<?php namespace App\Lemon\Repositories\System;
/*
 *
 * @author     Mark <zhaody901@qq.com>
 * @copyright  Copyright (c) 2013-2015 Sour Lemon Team
 */
class SysUrl {

	/**
	 * 设置要去的操作
	 * @param $url
	 */
	public static function setGo($url) {
		\Session::set(SysKernel::SESSION_GO, $url);
	}

	/**
	 * 设置要走向的地方
	 * @return mixed
	 */
	public static function getGo() {
		return \Session::get(SysKernel::SESSION_GO);
	}

	/**
	 * 获取要走向的地址, 并且清除地址
	 * @return mixed
	 */
	public static function getGoAndClear() {
		$go = \Session::get(SysKernel::SESSION_GO);
		\Session::remove(SysKernel::SESSION_GO);
		return $go;
	}
}