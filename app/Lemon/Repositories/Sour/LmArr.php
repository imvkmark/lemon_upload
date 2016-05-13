<?php namespace App\Lemon\Repositories\Sour;


/**
 * 数组相关操作
 * Class Arr
 * @package App\Lemon\Helper
 */
class LmArr {

	/**
	 * 数组一维化
	 * @param $array
	 * @return array|bool
	 */
	public static function flatten($array) {
		if (!is_array($array)) {
			return false;
		}
		$result = [];
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, self::flatten($value));
			} else {
				$result[$key] = trim($value);
			}
		}
		return $result;
	}

	/**
	 * 拼合数组, 支持多维拼合
	 * @param string $join
	 * @param array  $array
	 * @return string
	 */
	public static function combine(array $array, $join = ',') {
		$arr = self::flatten($array);
		return implode($join, $arr);
	}


	/**
	 * 根据数组生成自定义key序列
	 * array('name'=>'mark Zhao') => name|mark Zhao
	 * @param $array
	 * @return string
	 */
	public static function genKey($array) {
		if ($array) {
			$str = '';
			foreach ($array as $key => $value) {
				if (is_numeric($key)) continue;
				if (!$value) $value = 0;
				$str .= $key . '|' . $value . ';';
			}
			return rtrim($str, ';');
		} else {
			return '';
		}
	}

	/**
	 * 提取指定的键/值来提取数组
	 * @param array $array
	 * @param       $field_key
	 * @param       $field_value
	 * @param bool  $replace
	 * @return array
	 */
	public static function pluck(array $array, $field_key, $field_value = '*', $replace = true) {
		$tmp_arr = [];
		foreach ($array as $single) {
			if (isset($single[$field_key])) {
				if (!$replace) {
					if (isset($tmp_arr[$single[$field_key]])) continue;
				}
				$value = '';
				if ($field_value == '*') {
					$value = $single;
				} else if (strpos($field_value, ',') !== false) { // has ',' may be array
					$fields = explode(',', $field_value);
					$value  = [];
					foreach ($fields as $field) {
						if (isset($single[$field])) $value[$field] = $single[$field];
					}
				} else if (isset($single[$field_value])) {
					$value = $single[$field_value];
				}
				$tmp_arr[$single[$field_key]] = $value;
			}
		}
		return $tmp_arr;
	}

	/**
	 * 返回字串
	 * @param $array
	 * @return string
	 */
	public static function toStr($array) {
		if (is_array($array)) {
			return self::combine($array);
		} else {
			return $array;
		}
	}

	/**
	 * 返回kv结构字串
	 * @param $array
	 * @return string
	 */
	public static function toKvStr($array) {
		$return = '';

		if (is_array($array)) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$return .= $key . '=' . self::toKvStr($value) . ',';
				} else {
					$return .= $key . '=' . $value . ',';
				}
			}
		} else {
			$return .= $array . ',';
		}
		return rtrim($return, ',');
	}

	/**
	 * 首字母分割单词
	 * @param     $array
	 * @param int $split
	 * @return array
	 */
	public static function firstSplit($array, $split = 3) {
		//  计算总数
		sort($array);
		$num     = count($array);
		$partNum = $num / $split;
		$temp    = [];
		for ($i = 1; $i <= $split; $i++) {
			$temp[$i] = [];
			while (is_array($array) && !empty($array)) {
				if (count($temp[$i]) <= $partNum) {
					$temp[$i][] = array_shift($array);
				} else {
					break;
				}
			}
			if (!empty($temp[$i])) {
				$clone                                                    = $temp[$i];
				$firstLetter                                              = substr(array_shift($clone), 0, 1);
				$lastLetter                                               = substr(array_pop($clone), 0, 1);
				$temp[ucfirst($firstLetter) . '-' . ucfirst($lastLetter)] = $temp[$i];
				unset($temp[$i]);
			}
		}
		return $temp;

	}
	
	
	/**
	 * 键转换
	 * @param $array
	 * @param $key_convert
	 * @return array
	 */
	public static function keyConvert($array, $key_convert) {
		if (!empty($array)) {
			foreach ($array as $key => $arr) {
				foreach ($key_convert as $key_from => $key_to) {
					if (isset($arr[$key_from])) {
						$arr[$key_to] = $arr[$key_from];
						unset($arr[$key_from]);
					}
				}
				$array[$key] = $arr;
			}
			return $array;
		}
		return [];
	}

	/**
	 * 获取对象key
	 * @param $object
	 * @param $keys
	 * @return array
	 */
	public static function objByKey($object, $keys) {
		$return = [];
		if ($object) {
			foreach ($object as $obj) {
				$arr = [];
				foreach ($keys as $k => $v) {
					$arr[$v] = $obj->$v;
				}
				$return[] = $arr;
			}
		}
		return $return;
	}
}