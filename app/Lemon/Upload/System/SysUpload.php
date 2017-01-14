<?php namespace App\Lemon\Upload\System;

use App\Lemon\Repositories\Sour\LmArr;
use App\Lemon\Repositories\Sour\LmEnv;
use App\Lemon\Repositories\Sour\LmUtil;
use App\Lemon\Repositories\System\SysCrypt;

/**
 * 图片上传处理
 * Class SysUpload
 * @package App\Lemon\Upload\System;
 */
class SysUpload {


	/**
	 * 根据给定的URL, 然后返回地址
	 * @param string $input_url 图片路径
	 * @return string
	 */
	public static function url($input_url) {
		if (!$input_url) {
			return config('app.url_image') . '/lemon/fw/nopic.gif';
		}

		$path = self::relativePath($input_url);
		if (LmUtil::isUrl($path)) {
			return $path;
		} else {
			switch (config('upload.type')) {
				case 'qiniu':
					return config('upload.qiniu_url') . '/' . $path;
					break;
				case 'lemon':
				default:
					$subDirectory = 'thumber/config/';
					return config('app.url') . '/' . $subDirectory . $path;
					break;
			}
		}
	}


	/**
	 * 除去目录之外的相对路径
	 * @param $path_or_url
	 * @return mixed
	 */
	public static function relativePath($path_or_url) {
		$path_or_url = str_replace([
			'\\"',
		], [
			'',
		], $path_or_url);
		if (LmUtil::isUrl($path_or_url)) {
			/*
			|--------------------------------------------------------------------------
			| 支持 l5-thumber 的替换方式
			|--------------------------------------------------------------------------
			| 201510/17/demo,h_150,w_690.jpg
			| 201510/17/demo.jpg
			*/
			$path_or_url = preg_replace('/(,.*?\.)/U', '.', $path_or_url);
		}
		$path_or_url = ltrim($path_or_url, '/');
		return $path_or_url;
	}

	/**
	 * 存储的磁盘
	 * @return mixed|string
	 */
	public static function disk() {
		return config('upload.server_disk');
	}


	/**
	 * 生成上传的token
	 * @param $app_key
	 * @return string
	 */
	public static function genUploadToken($app_key) {
		return SysCrypt::encode('upload|' . $app_key . '|' . str_random() . '|' . LmEnv::time(), config('app.key'));
	}

	/**
	 * 计算请求签名
	 * @param $app_key
	 * @param $app_secret
	 * @param $timestamp
	 * @param $version
	 * @return string
	 */
	public static function calcSign($app_key, $app_secret, $timestamp, $version) {
		$array = [
			'timestamp'  => $timestamp,
			'app_key'    => $app_key,
			'app_secret' => $app_secret,
			'version'    => $version,
		];
		ksort($array);
		$str = LmArr::toKvStr($array);
		return sha1(md5($str));
	}
}