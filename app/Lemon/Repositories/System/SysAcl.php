<?php namespace App\Lemon\Repositories\System;

use App\Lemon\Repositories\Sour\LmFile;
use App\Models\BaseConfig;
use App\Models\PamAccount;
use App\Models\PamRole;
use Illuminate\Database\Eloquent\Collection;

/**
 * 权限控制
 * Class SysAcl
 * @package App\Lemon\Project
 */
class SysAcl {

	const TYPE_API = 'api';

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
					$links[$key] = $ctl['title'];
				}
			}
			\Cache::forever($cacheKey, $links);
		}
		$cache = \Cache::get($cacheKey);

		return $route
			? isset($cache[$route]) ? $cache[$route] : ''
			: $cache;
	}

	/**
	 * 获取缓存
	 * @param $type
	 * @param $role_id
	 * @return mixed
	 */
	public static function getCache($type, $role_id) {
		static $cache;
		if ($role_id) {
			$cacheKey = self::CACHE_PREFIX . $type . '_' . $role_id;
		} else {
			$cacheKey = self::CACHE_PREFIX . $type;
		}
		if (!isset($cache[$cacheKey])) {
			if (!\Cache::has($cacheKey)) {
				$cacheData = self::menu($type, $role_id, false);
				\Cache::forever($cacheKey, $cacheData);
			}
			$cache[$cacheKey] = \Cache::get($cacheKey);
		}
		return $cache[$cacheKey];
	}

	/**
	 * 清除缓存
	 */
	public static function reCache() {
		$roles = PamRole::getAll();
		$keys  = [PamAccount::ACCOUNT_TYPE_DESKTOP, PamAccount::ACCOUNT_TYPE_DEVELOP, PamAccount::ACCOUNT_TYPE_FRONT];
		foreach ($roles as $role) {
			$keys[] = $role['account_type'] . '_' . $role['role_id'];
			$keys[] = $role['account_type'];
		}
		$collection = new Collection($keys);
		$collection->each(function ($key) {
			\Cache::forget(self::CACHE_PREFIX . $key);
		});
	}

	public static function permission($type = PamAccount::ACCOUNT_TYPE_DESKTOP) {
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
				$key      = basename($f, '.php');
				$typeData =
					array_merge($typeData, self::operation($typeDir . '/' . $key, null, false, false));
			}
		}
		return $typeData;
	}

	/**
	 * 获取菜单
	 * @param string     $type
	 * @param PamAccount $user
	 * @param bool|true  $is_menu
	 * @return mixed|string
	 */
	public static function menu($type = PamAccount::ACCOUNT_TYPE_DESKTOP, $user = null, $is_menu = true) {
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
				$typeData[$key] = self::operation($typeDir . '/' . $key, $user, $is_menu);
			}
		}

		// 格式化菜单项目
		foreach ($menu as $menu_group => $group) {
			$menuLink   = [];
			$links      = [];
			$link_count = 0;
			foreach ($group['group'] as $type_key) {
				if (!isset($typeData[$type_key]) || empty($typeData[$type_key])) {
					continue;
				}
				$menuLink[$type_key] = $typeData[$type_key];

				$link_count += count($typeData[$type_key]['sub_group']) + count($typeData[$type_key]['direct']);
				$links = array_merge($links, $typeData[$type_key]['sub_group'], $typeData[$type_key]['direct']);
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
	 * @return array
	 */
	public static function operation($file_relative, $user = null, $is_menu = true, $with_group = true) {
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
		$operationOnly      = [];
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
				if ($with_group) {
					if (isset($op_define['group']) && $op_define['group']) {
						$operationWithGroup['sub_group'][$route] = $singleDefine;
					} else {
						$operationWithGroup['direct'][$route] = $singleDefine;
					}
				} else {
					$operationOnly[$route] = $singleDefine;
				}

			}
		}
		if ($with_group) {
			return $operationWithGroup;
		} else {
			return $operationOnly;
		}
	}
}