<?php namespace App\Lemon\Repositories\System;

/**
 * 权限控制
 * Class SysSearch
 * @package App\Lemon\Project
 */
class SysSearch {

	/**
	 * 获取排序的key
	 * @return mixed|string
	 */
	public static function key() {
		$order = \Input::get('_order');
		if (!$order) {
			return '';
		} else {
			if (strpos($order, '_desc') !== false) {
				return str_replace('_desc', '', $order);
			}
			if (strpos($order, '_asc') !== false) {
				return str_replace('_asc', '', $order);
			}
			return '';
		}
	}

	/**
	 * 排序类型
	 * @return string
	 */
	public static function order() {
		$order = \Input::get('_order');
		if (strpos($order, '_desc') !== false) {
			return 'desc';
		}
		if (strpos($order, '_asc') !== false) {
			return 'asc';
		}
		return 'desc';
	}
}