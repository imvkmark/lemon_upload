<?php
use App\Lemon\Repositories\Sour\LmEnv;
use App\Lemon\Repositories\Sour\LmUtil;
use App\Lemon\Repositories\System\SysAcl;
use App\Lemon\Repositories\System\SysCrypt;
use App\Models\BaseConfig;
use App\Models\DailianOrder;
use App\Models\PamRole;
use App\Models\PamRoleAccount;
use Carbon\Carbon;

/**
 * 项目用到的函数
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @param $order
 * @return string
 */
function order_left_time($order) {
	$time    = LmEnv::time();
	$endTime = Carbon::createFromFormat('Y-m-d H:i:s', $order['ended_at'])->timestamp;
	if ($endTime < $time) { // 超时
		$str        = '<span style="color:red">';
		$intvaltime = $time - $endTime;
	} else {
		$str        = '<span style="color:green">';
		$intvaltime = $endTime - $time;
	}
	$month = floor($intvaltime / (30 * 24 * 60 * 60));
	$day   = floor(($intvaltime % (30 * 24 * 60 * 60)) / (24 * 60 * 60));
	$hour  = floor(($intvaltime % (24 * 60 * 60)) / (60 * 60));
	$min   = floor(($intvaltime % (60 * 60)) / (60));
	$str .= $month ? $month . '月' : '';
	$str .= $day ? $day . '天' : '';
	$str .= $hour ? $hour . '小时' : '';
	$str .= $min ? $min . '分' : '';
	$str .= '</span>';
	return $str;
}

function order_tag_decode($tag) {
	if ($tag) {
		return str_replace('|', ', ', trim($tag, '|'));
	} else {
		return '';
	}
}

function order_tag_encode($tag) {
	if ($tag) {
		return '|' . implode('|', $tag) . '|';
	} else {
		return '';
	}
}

/**
 * 订单周期
 * @param \App\Models\DailianOrder $order
 * @param string                   $return_type
 * @return mixed
 */
function order_period($order, $return_type = DailianOrder::LEFT_TIME_TYPE_HOUR) {
	$hour = LmUtil::toHour($order->order_p_hour, $order->order_p_day);
	switch ($return_type) {
		case DailianOrder::LEFT_TIME_TYPE_SECOND:
			return $hour * 60 * 60;
			break;
		case DailianOrder::LEFT_TIME_TYPE_MINUTE:
			return $hour * 60;
			break;
		case DailianOrder::LEFT_TIME_TYPE_HOUR:
		default:
			return $hour;
			break;
	}
}

/**
 * 取消类型颜色区分
 * @param $order
 * @return string
 */
function order_cancel_class($order) {
	$class = '';
	if ($order->cancel_type == DailianOrder::CANCEL_TYPE_KF) {
		$class = 'color-kf ';
	}
	if ($order->cancel_type == DailianOrder::CANCEL_TYPE_PUB_DEAL) {
		$class = 'color-pub ';
	}
	if ($order->cancel_type == DailianOrder::CANCEL_TYPE_SD_DEAL) {
		$class = 'color-sd ';
	}
	return $class;
}

/**
 * 发单者使用的
 * @param $order DailianOrder
 * @return string
 */
function order_pub_class($order) {
	$class = '';
	if ($order->msg_sd_talk == 'Y') {
		$class .= 'info ';
	}
	if ($order->is_exception == 'Y') {
		$class .= 'danger ';
	}
	return $class;
}


/**
 * 接单者使用的提示
 * @param $order DailianOrder
 * @return string
 */
function order_sd_class($order) {
	$class = '';
	if ($order->msg_pub_talk == 'Y') {
		$class .= 'info ';
	}
	if ($order->is_exception == 'Y') {
		$class .= 'danger ';
	}
	return $class;
}

/**
 * 是否发布者
 * @param $item App\Models\DailianOrder
 * @param $account_id
 * @return bool
 */
function is_pub($item, $account_id) {
	return $item->account_id == $account_id;
}

/**
 * 是否接单人
 * @param $item App\Models\DailianOrder
 * @param $account_id
 * @return bool
 */
function is_sd($item, $account_id) {
	return $item->sd_account_id == $account_id;
}

/**
 * 获取头像地址
 * @param        $account_id
 * @param string $size
 * @return string
 */

function avatar($account_id, $size = 'middle') {
	$disk = \Storage::disk('public');
	if (!$disk->exists('uploads/avatar/' . $account_id . '.png')) {
		$avatar = config('app.url_image') . "/1dailian/avatar." . $size . ".jpg";
	} else {
		$avatar = config('url') . 'uploads/avatar/' . $account_id . '.png';
	}
	return $avatar;
}

/**
 * 通过key 获取 url
 * @param $key
 * @return string
 */
function attachment_url($key) {
	if (strlen($key) <= strlen('uploads/thumb/201512/17/249/LRVHewjE2m.jpg') + 8) {
		$url = config('app.url') . '/' . $key;
	} else {
		$copyright = SysCrypt::copyright();
		$attach    = SysCrypt::decode($key, $copyright);
		$url       = config('app.url') . '/' . $attach;
	}
	return $url;
}

/**
 * 获取网站配置
 * @param null $key
 * @return mixed|null
 */
function site($key = null) {
	static $site;
	if (!$site) {
		$site = BaseConfig::getCache('site');
	}
	return $key
		? isset($site[$key]) ? $site[$key] : null
		: $site;
}



/**
 * 检查路由权限
 * @param $role_id
 * @param $route
 * @return bool
 */
function check_auth($role_id, $route) {
	static $authes;
	if (!isset($authes[$role_id])) {
		$authes[$role_id] = BaseConfig::roleMenu($role_id);
	}
	if (strpos($route, '||') !== false) {
		$or      = \App\Lemon\Repositories\Sour\LmStr::separate('||', $route);
		$default = false;
		foreach ($or as $_or) {
			if (isset($authes[$role_id][$_or]) && $authes[$role_id][$_or]) {
				$default = $default || true;
			} else {
				$default = $default || false;
			}
			return $default;
		}
		return false;
	} else if (strpos($route, '&&')) {
		$and     = \App\Lemon\Repositories\Sour\LmStr::separate('&&', $route);
		$default = true;
		foreach ($and as $_and) {
			if (isset($authes[$role_id][$_and]) && $authes[$role_id][$_and]) {
				$default = $default && true;
			} else {
				$default = $default && false;
			}
		}
		return $default;
	} else {
		// 存在 route, 并且 权限存在
		return $route && ((isset($authes[$role_id][$route]) && $authes[$role_id][$route]) || !isset($authes[$role_id][$route]));
	}

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

	$uploadUrl = route('vendor.upload_image');
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



/**
 * key
 */
function upload_token() {
	return \Imvkmark\SlUpload\Helper\SlUpload::genUploadToken();
}