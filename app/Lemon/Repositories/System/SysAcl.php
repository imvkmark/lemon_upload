<?php namespace App\Lemon\Repositories\System;

use App\Lemon\Repositories\Sour\LmFile;
use App\Models\PamAccount;

/**
 * 权限控制
 * Class SysAcl
 * @package App\Lemon\Project
 */
class SysAcl {

	const ACL_PATH = 'Lemon/Suit/Acl';

	const CACHE_PREFIX = 'acl_';

	/**
	 * 获取缓存
	 * @param string      $type  类型
	 * @param null|string $route 路由名称
	 * @return mixed
	 */
	public static function getTitleCache($type = 'desktop', $route = null) {
		$cacheKey = cache_name(__CLASS__, '_title_' . $type);

		if (!\Cache::has($cacheKey)) {
			$cacheData = self::permission($type);
			$links     = [];
			if (is_array($cacheData)) {
				foreach ($cacheData as $key => $ctl) {
					$links[$key] = isset($ctl['title']) ? $ctl['title'] : '';
				}
			}
			\Cache::forever($cacheKey, $links);
		}
		$cache = \Cache::get($cacheKey);

		return $route
			? isset($cache[$route]) ? $cache[$route] : ''
			: $cache;
	}


	public static function getPermissionCache($type = 'desktop') {
		$cacheKey = cache_name(__CLASS__, '_permission_' . $type);

		if (!\Cache::has($cacheKey)) {
			$cacheData = self::permission($type);
			$links     = [];
			if (is_array($cacheData)) {
				foreach ($cacheData as $key => $ctl) {
					$links[$key] = true;
				}
			}
			\Cache::forever($cacheKey, $links);
		}
		$cache = \Cache::get($cacheKey);
		return $cache;
	}

	public static function reCache() {
		// title Cache
		\Cache::forget(cache_name(__CLASS__, '_title_desktop'));
		\Cache::forget(cache_name(__CLASS__, '_title_develop'));
		\Cache::forget(cache_name(__CLASS__, '_title_front'));
		// permission cache
		\Cache::forget(cache_name(__CLASS__, '_permission_desktop'));
		\Cache::forget(cache_name(__CLASS__, '_permission_develop'));
		\Cache::forget(cache_name(__CLASS__, '_permission_front'));
	}

	/**
	 * 获取菜单
	 * @param string     $type
	 * @param PamAccount $user
	 * @param bool|true  $is_menu
	 * @param bool       $with_group
	 * @return mixed|string
	 */
	public static function menu($type = 'desktop', $user = null, $is_menu = true, $with_group = false) {
		// define file
		$file = app_path(self::ACL_PATH . '/' . $type . '.php');

		// directory
		$typeDir = ucfirst($type);
		$dir     = app_path(self::ACL_PATH . '/' . $typeDir);
		if (!is_dir($dir)) {
			return false;
		}
		if (!is_file($file)) {
			return false;
		}

		// 菜单项目配置
		$menu = LmFile::readPhp($file);
		if (!is_array($menu)) {
			return false;
		}


		// 子目录扫描并获取可以操作的项目
		$typeData  = [];
		$typeFiles = LmFile::subFile($dir);
		if (is_array($typeFiles) && !empty($typeFiles)) {
			foreach ($typeFiles as $f) {
				$key            = basename($f, '.php');
				$typeData[$key] = self::operation($typeDir . '/' . $key, $user, $is_menu, $with_group, false);
			}
		}

		// 格式化菜单项目
		foreach ($menu as $menu_group => $group) {
			$menuLink   = [];
			$links      = [];
			$link_count = 0;
			foreach ($group['group'] as $route) {
				if (!isset($typeData[$route]) || empty($typeData[$route])) {
					continue;
				}
				$menuLink[$route] = $typeData[$route];

				$link_count += count($typeData[$route]['sub_group']) + count($typeData[$route]['direct']);
				$links = array_merge($links, $typeData[$route]['sub_group'], $typeData[$route]['direct']);
			}
			$menu[$menu_group]['menu_link']  = $menuLink;
			$menu[$menu_group]['link_count'] = $link_count;
			if ($link_count) {
				$menu[$menu_group]['route'] = array_keys($links)[0];
			}
		}
		return $menu;
	}


	/**
	 * 根据类型/ 路由 获取定义的数据
	 * @param             $file_relative
	 * @param PamAccount  $user
	 * @param bool|true   $is_menu
	 * @param bool        $with_group
	 * @param bool        $with_permission 是否包含权限
	 * @return array
	 */
	public static function operation($file_relative, $user = null, $is_menu = true, $with_group = true, $with_permission = false) {

		$file_relative = explode('/', $file_relative);
		$type          = ucfirst($file_relative[0]);
		$filePath      = app_path(self::ACL_PATH . '/' . $type . '/' . $file_relative[1] . '.php');

		$define_data = [];
		if (file_exists($filePath)) {
			$define_data = LmFile::readPhp($filePath);
		} else {
			return $define_data;
		}


		$operationWithGroup = [
			'title' => $define_data['title'],
		];

		if (isset($define_data['operation'])) {
			$operationWithGroup['sub_group'] = [];
			$operationWithGroup['direct']    = [];

			foreach ($define_data['operation'] as $op_key => $op_define) {
				// 剔除非菜单项目

				if ($is_menu) {
					if (!isset($op_define['menu']) || !$op_define['menu'] || $op_define['menu'] == false) {
						continue;
					}
				}

				if (!$with_permission) { // 不包含权限
					if (isset($op_define['permission']) && $op_define['permission'] = true) {
						continue;
					}
				}

				// 组合路由
				$route = $define_data['route'] . '.' . $op_key;

				if ($user && !$user->capable($route)) {
					continue;
				}
				$singleDefine = [
					'title'       => $op_define['title'],
					'route'       => $route,
					'group_title' => $define_data['title'],
					'menu'        => (isset($op_define['menu']) && $op_define['menu']) ? $op_define['menu'] : 0,
					'param'       => isset($op_define['param']) ? $op_define['param'] : '',
				];

				if ($with_group && isset($op_define['group']) && $op_define['group']) {
					$operationWithGroup['sub_group'][$route] = $singleDefine;
				} else {
					$operationWithGroup['direct'][$route] = $singleDefine;
				}

			}
		}

		return $operationWithGroup;

	}

	/**
	 * 权限
	 * @param string $type
	 * @param null   $user
	 * @return array|bool
	 */
	public static function permission($type = 'desktop', $user = null) {
		$typeDir = ucfirst($type);
		$dir     = app_path(self::ACL_PATH . '/' . $typeDir);
		if (!is_dir($dir)) {
			return false;
		}
		// 子目录扫描并获取可以操作的项目
		$typeData  = [];
		$typeFiles = LmFile::subFile($dir);
		if (is_array($typeFiles) && !empty($typeFiles)) {
			foreach ($typeFiles as $f) {
				$key       = basename($f, '.php');
				$operation = self::operation($typeDir . '/' . $key, $user, false, false, false);
				$typeData  = array_merge($typeData, $operation['direct']);
			}
		}
		return $typeData;
	}
}
