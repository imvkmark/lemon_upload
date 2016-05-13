<?php namespace App\Lemon\Repositories\Sour;

use Carbon\Carbon;

class LmTime {

	/**
	 * 标准的UNIX时间戳
	 * 获得当前格林威治时间的时间戳
	 * @return int
	 */
	public static function gmTime() {
		return time() - date('Z');
	}

	/**
	 * @param        $date
	 * @param string $sep
	 * @return bool 检测是否是标准时间.
	 */
	public static function isDate($date, $sep = '-') {
		// 时间为空
		if (empty($date)) return false;
		// 长度大于 10
		if (strlen($date) > 10) return false;
		list($year, $month, $day) = explode($sep, $date);
		return checkdate($month, $day, $year);
	}

	/**
	 * 格式化时间
	 * @param int    $time
	 * @param string $format
	 * @return bool|string
	 */
	public static function datetime($time = 0, $format = '3-3') {
		$time = !empty($time) ? (is_numeric($time) ? $time : strtotime($time)) : LmEnv::time();//strotime强制将代入进来的时间格式都转成Unix时间戳
		switch ($format) {
			case '3-2':
				$df = 'Y-m-d H:i';
				break;
			case '2-2':
				$df = 'm-d H:i';
				break;
			case '2-3':
				$df = 'm-d H:i:s';
				break;
			case '2-4':
				$df = 'Y-m-d';
				break;
			case '3-3':
			default:
				$df = 'Y-m-d H:i:s';
				break;
		}
		return date($df, $time);
	}

	/**
	 * 自定义函数：time2string($second) 输入秒数换算成多少天/多少小时/多少分/多少秒的字符串
	 * @param $second
	 * @return string
	 */
	public static function time2string($second){
		$day    = floor($second/(3600*24));
		$second = $second%(3600*24);//除去整天之后剩余的时间
		$hour   = floor($second/3600);
		$second = $second%3600;//除去整小时之后剩余的时间
		$minute = floor($second/60);
		$second = $second%60;//除去整分钟之后剩余的时间
		//返回字符串
		//return $day.'天'.$hour.'小时'.$minute.'分'.$second.'秒';
		return $day.'天'.$hour.'小时';
	}

	/**
	 * 转换字符串形式的时间表达式为GMT时间戳
	 * @param $str
	 * @return bool|int|string
	 */
	public static function gmStr2Time($str) {
		$time = strtotime($str);

		if ($time > 0) {
			$time -= date('Z');
		}

		return $time;
	}

	/**
	 * 获得服务器的时区
	 * @return float|string
	 */
	public static function serverTimezone() {
		if (function_exists('date_default_timezone_get')) {
			return date_default_timezone_get();
		} else {
			return date('Z') / 3600;
		}
	}

	/**
	 * 一天的开始
	 * @param $date
	 * @return string
	 */
	public static function dayStart($date) {
		if (preg_match('/\d{4}-\d{2}-\d{2}/', $date)) {
			$date = Carbon::createFromFormat('Y-m-d', $date)->timestamp;
		}
		return date('Y-m-d', $date) . ' 00:00:00';
	}

	/**
	 * 一天的结束
	 * @param $date
	 * @return string
	 */
	public static function dayEnd($date) {
		if (preg_match('/\d{4}-\d{2}-\d{2}/', $date)) {
			$date = Carbon::createFromFormat('Y-m-d', $date)->timestamp;
		}
		return date('Y-m-d', $date) . ' 23:59:59';
	}

	/**
	 * 格式化日期
	 * @param int    $time
	 * @param string $format
	 * @return bool|string
	 */
	public static function format($time = 0, $format = "Y-m-d H:i") {
		//strtotime 强制将代入进来的时间格式都转成Unix时间戳
		$timestamp = !empty($time) ? (is_numeric($time) ? $time : strtotime($time)) : LmEnv::time();
		return date($format, $timestamp);
	}

	/**
	 * 空日期检测
	 * @param $date
	 * @return bool
	 */
	public static function isEmpty($date) {
		if (empty($date) or $date === '0000-00-00' or $date === '0000-00-00 00:00:00') {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $datetime
	 * @return int
	 */
	public static function datetimeToTimestamp($datetime) {
		return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->timestamp;
	}

	/**
	 * 时间戳转换成 datetime 类型
	 * @param $timestamp
	 * @return bool|string
	 */
	public static function timestampToDatetime($timestamp) {
		return self::format($timestamp, 'Y-m-d H:i:s');
	}

	/**
	 * 获取今日起始时间
	 * @param bool|false $unix
	 * @return int|string
	 */
	public static function todayStart($unix = false) {
		$Carbon = Carbon::now()->hour(0)->minute(0)->second(0);
		if ($unix) {
			return $Carbon->timestamp;
		} else {
			return $Carbon->toDateTimeString();
		}
	}

	/**
	 * 获取今日起始时间
	 * @param bool|false $unix
	 * @return int|string
	 */
	public static function todayEnd($unix = false) {
		$Carbon = Carbon::now()->hour(23)->minute(59)->second(59);
		if ($unix) {
			return $Carbon->timestamp;
		} else {
			return $Carbon->toDateTimeString();
		}
	}
}