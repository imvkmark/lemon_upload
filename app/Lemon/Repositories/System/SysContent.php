<?php namespace App\Lemon\Repositories\System;

use \App\Lemon\Repositories\Sour\LmStr;
use Sunra\PhpSimple\HtmlDomParser;

class SysContent {

	/**
	 * 清除链接
	 * @param $content
	 * @return mixed
	 */
	public static function clearLink($content) {
		$content = preg_replace("/<a[^>]*>/i", "", $content);
		return preg_replace("/<\/a>/i", "", $content);
	}

	/**
	 * 完善链接
	 * @param $url
	 * @return string
	 */
	public static function fixLink($url) {
		if (strlen($url) < 10) return '';
		return strpos($url, '://') === false ? 'http://' . $url : $url;
	}


	/**
	 * 将内容截取到介绍中
	 * @param string $content 有待截取的内容
	 * @param int    $length  带截取的长度
	 * @return mixed|string 截取内容的一部分
	 */
	public static function intro($content, $length = 0) {
		if ($length) {
			$content = str_replace([' ', '[pagebreak]'], ['', ''], $content);
			$intro   = trim(LmStr::trimEOL(strip_tags($content)));
			// 删除实体
			$intro = preg_replace("/&([a-z]{1,});/", '', $intro);
			return nl2br(LmStr::cut($intro, $length, '...'));
		} else {
			return '';
		}
	}

	/**
	 * 图片自适应分类
	 * @param $content
	 * @return string
	 */
	public static function imgResponsive($content) {
		if (!$content) {
			return '';
		}
		$htmlDom = HtmlDomParser::str_get_html($content);
		$images  = $htmlDom->find('img');
		if ($images) {
			foreach ($images as $img_key => $img) {
				$htmlDom->find('img', $img_key)->class;
				$class = $img->class ? $img->class . ' img-responsive' : 'img-responsive';
				$src   = thumb($img->src);

				$htmlDom->find('img', $img_key)->src   = $src;
				$htmlDom->find('img', $img_key)->class = $class;
			}
			ob_start();
			echo $htmlDom;
			$content = ob_get_clean();
			return $content;
		} else {
			return $content;
		}

	}
}