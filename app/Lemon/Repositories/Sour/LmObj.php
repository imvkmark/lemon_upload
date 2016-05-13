<?php namespace App\Lemon\Repositories\Sour;


/**
 * Class LmObj
 * @package App\Lemon\Helper
 */
class LmObj {

	/**
	 * 获取对象key
	 * @param $object
	 * @param $keys
	 * @return array
	 */
	public static function getByKey($object, $keys) {
		$return = [];
		if ($object) {
			foreach ($object as $obj) {
				$arr = [];
				foreach ($keys as $k => $v) {
					if (is_numeric($k)) {
						$arr[$v] = $obj->$v;
					} else {
						$arr[$v] = $obj->$k;
					}
				}
				$return[] = $arr;
			}
		}
		return $return;
	}

}