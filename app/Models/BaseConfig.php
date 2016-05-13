<?php namespace App\Models;


/**
 * App\Models\BaseConfig
 *
 * @property integer $conf_id    配置id
 * @property string  $conf_group 配置分组
 * @property string  $conf_name  配置名称
 * @property string  $conf_value 配置值
 * @property string  $conf_desc  配置介绍
 * @property string  $is_enable  是否起作用
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BaseConfig enable()
 */
class BaseConfig extends \Eloquent {

	const CACHE_PREFIX = 'config_';

	protected $table = 'base_config';

	protected $primaryKey = 'conf_id';

	protected $fillable = [
		'conf_group',
		'conf_name',
		'conf_value',
	];

	public $timestamps = false;

	public function scopeEnable() {
		return $this->where('is_enable', 'Y');
	}


	public static function getCache($group) {
		static $cache;
		if (!$group) {
			return [];
		}
		$cacheKey = self::CACHE_PREFIX . $group;
		if (!isset($cache[$group])) {
			if (!\Cache::has($cacheKey)) {
				$config    = self::where('is_enable', 'Y')
					->where('conf_group', $group)
					->get();
				$cacheData = [];
				foreach ($config as $conf) {
					$cacheData[strtolower($conf->conf_name)] = $conf->conf_value;
				}
				\Cache::forever($cacheKey, $cacheData);
			}
			$cache[$group] = \Cache::get($cacheKey);
		}
		return $cache[$group];
	}

	/**
	 * 清空缓存
	 * @param null $group
	 */
	public static function reCache($group = null) {
		if ($group) {
			\Cache::forget(self::CACHE_PREFIX . $group);
		} else {
			$groups = self::distinct()->lists('conf_group');
			$groups->each(function ($confGroup) {
				\Cache::forget(self::CACHE_PREFIX . $confGroup);
			});
		}
	}

	/**
	 * 更新配置
	 * @param $configs
	 * @param $group
	 */
	public static function configUpdate($configs, $group) {
		self::whereIn('conf_name', array_keys($configs))->where('conf_group', $group)->delete();

		$batch = [];
		foreach ($configs as $key => $value) {
			$batch[] = ['conf_group' => $group, 'conf_name' => $key, 'conf_value' => $value,];
		}
		self::insert($batch);
	}

	/**
	 * 获取组可用权限, 采用 K = 1 的方式, 来检测 K 是否存在
	 * @param $role_id
	 * @return mixed
	 */
	public static function roleMenu($role_id) {
		$role = BaseConfig::getCache('role');
		if ($role && isset($role['menu-' . $role_id])) {
			return json_decode($role['menu-' . $role_id], true);
		} else {
			return [];
		}
	}
}
