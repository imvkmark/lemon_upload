<?php namespace App\Lemon\Repositories\System;

use App\Lemon\Repositories\Sour\LmArr;
use App\Lemon\Repositories\Sour\LmFile;
use App\Models\PamRole;

/**
 * 流程控制
 * Class SysProgress
 * @package App\Lemon\Project
 */
class SysProgress {


	const PROGRESS_PATH = 'Lemon/Suit/Progress';


	/**
	 * 对条目的权限检测
	 * 如果没有限制, 则一切操作都是可以的, 否则只允许在操作中的配置.
	 * @param \Illuminate\Database\Eloquent\Model $obj
	 * @param                                     $act
	 * @param null                                $role_id
	 * @return bool
	 */
	public static function act($obj, $act, $role_id = null, $table = null, $kv_match = null) {
		$tableName  = $table ?: $obj->getTable();

		// 操作
		$fieldOp = self::objOperation($obj, $tableName, $act);

		if (config('lemon.debug_operation')) {
			\Cw::info([
				'当前状态'  => '对象角色权限',
				'是否允许'  => in_array($act, $fieldOp),
				'可允许操作' => $fieldOp,
			]);
		}

		if (is_numeric($role_id)) {
			// filter action
			if (!is_super($role_id)) {
				$accountType = PamRole::getAccountTypeByRoleId($role_id);
				$roleAuth    = SysAcl::key($accountType, $role_id);
				$fieldOp     = array_intersect($roleAuth, $fieldOp);
			}

			if (config('lemon.debug_operation')) {
				\Cw::info([
					'当前状态'  => '角色权限检测 - 配置取值',
					'是否允许'  => in_array($act, $fieldOp),
					'可允许操作' => $fieldOp,
				]);
			}
		} elseif (is_array($role_id)) {

			if (config('lemon.debug_operation')) {
				\Cw::info([
					'当前状态'  => '角色权限检测 - 直接传值',
					'是否允许'  => in_array($act, $role_id),
					'可允许操作' => $role_id,
				]);
			}

			$fieldOp = array_intersect($role_id, $fieldOp);
		}



		$actionsKey = array_flip($fieldOp);
		$kv         = true;


		// @see http://medoo.in/api/where
		if ($kv_match && is_array($kv_match)) {
			foreach ($kv_match as $pk => $pv) {
				preg_match('/(#?)([\w\.]+)(\[(\>|\>\=|\<|\<\=|\!|\<\>|\>\<|\!?~)\])?/i', $pk, $match);
				$field = $match[2];
				if (isset($match[2]) && $obj->$field) {
					if (isset($match[4])) {
						switch ($match[4]) {
							case '!':
								$kv = $kv && $obj->$field != $kv_match[$match[0]];
								break;
							case '<':
								$kv = $kv && $obj->$field < $kv_match[$field];
								break;
							case '>':
								$kv = $kv && $obj->$field > $kv_match[$field];
								break;
							case '<>': // BETWEEN
								if (!is_array($kv_match[$field])) continue;
								$kv = $kv && $obj->$field > $kv_match[$field][0];
								$kv = $kv && $obj->$field < $kv_match[$field][1];
								break;
							case '=<>=': // BETWEEN
								if (!is_array($kv_match[$field])) continue;
								$kv = $kv && $obj->$field >= $kv_match[$field][0];
								$kv = $kv && $obj->$field <= $kv_match[$field][1];
								break;
							case '<>=': // BETWEEN
								if (!is_array($kv_match[$field])) continue;
								$kv = $kv && $obj->$field >= $kv_match[$field][0];
								$kv = $kv && $obj->$field < $kv_match[$field][1];
								break;
							case '=<>': // BETWEEN
								if (!is_array($kv_match[$field])) continue;
								$kv = $kv && $obj->$field > $kv_match[$field][0];
								$kv = $kv && $obj->$field <= $kv_match[$field][1];
								break;
							case '><': // NOT BETWEEN
								$kv = $kv && $obj->$field < $kv_match[$field][0];
								$kv = $kv && $obj->$field > $kv_match[$field][1];
								break;
							case '=><=': // NOT BETWEEN
								$kv = $kv && $obj->$field <= $kv_match[$field][0];
								$kv = $kv && $obj->$field >= $kv_match[$field][1];
								break;
							case '=><':
								$kv = $kv && $obj->$field < $kv_match[$field][0];
								$kv = $kv && $obj->$field >= $kv_match[$field][1];
								break;
							case '><=':
								$kv = $kv && $obj->$field <= $kv_match[$field][0];
								$kv = $kv && $obj->$field > $kv_match[$field][1];
								break;
							case '~':
								$tmp_bool = strpos($obj->$field, $kv_match[$match[0]]);
								if ($tmp_bool === 0) {
									$tmp_bool = true;
								}
								$kv = $kv && $tmp_bool;
								break;
						}
					} else {
						$kv = $kv && $obj->$field == $kv_match[$field];
					}
				}
			}
		}
		return isset($actionsKey[$act]) && $kv;
	}

	/**
	 * 本对象当前的可操作权限
	 * @param \Eloquent $obj
	 * @param null      $table
	 * @return array
	 */
	public static function objOperation($obj, $table = null, $check_operation = null) {
		$tableName  = $table ?: $obj->getTable();
		$operations = self::operation($tableName);
		if (!isset($operations['_all_operations'])) {
			return [];
		}
		$allOperations = $operations['_all_operations'];
		unset($operations['_all_operations']);
		if (config('lemon.debug_operation')) {
			$flattenUniq = array_unique(LmArr::flatten($operations));
			\Cw::info([
				'当前状态' => '全部操作',
				'是否允许' => in_array($check_operation, $flattenUniq),
				'全部操作' => $flattenUniq,
			]);
		}
		$allOperationsReverse = array_flip($allOperations);
		// 操作
		$fieldOp = $allOperations;

		foreach ($operations as $key => $op) {
			// 对象的值
			$objVal = is_object($obj) ? $obj->$key : $obj[$key];
			if ($objVal) {
				// 字段可操作的
				$fieldOpSelection = $operations[$key];

				if (isset($fieldOpSelection[$objVal]) && !empty($fieldOpSelection[$objVal])) {
					$opSelection = $fieldOpSelection[$objVal];

					if (!isset($opSelection['_only']) && !isset($opSelection['_all']) && !isset($opSelection['_except'])) {
						$fieldOp = [];
					} else {
						$fieldOp = array_intersect($fieldOp, $allOperations);
					}

					if (isset($opSelection['_all'])) {
						$fieldOp = array_intersect($fieldOp, $allOperations);
					}
					if (isset($opSelection['_except'])) {
						$fieldOp_exceptReverse = $allOperationsReverse;
						if (is_array($opSelection['_except'])) {
							foreach ($opSelection['_except'] as $_ev) {
								if (isset($allOperationsReverse[$_ev])) {
									unset($fieldOp_exceptReverse[$_ev]);
								}
							}
						}
						$fieldOp_except = array_flip($fieldOp_exceptReverse);
						$fieldOp        = array_intersect($fieldOp, $fieldOp_except);
					}


					if (isset($opSelection['_only'])) {
						$fieldOp_only = [];
						if (is_array($opSelection['_only'])) {
							foreach ($opSelection['_only'] as $_ev) {
								if (isset($allOperationsReverse[$_ev])) {
									$fieldOp_only[] = $_ev;
								}
							}
						}
						$fieldOp = array_intersect($fieldOp, $fieldOp_only);
					}
				} else {
					$fieldOp = [];
				}

				// debug for quick op
				if (config('lemon.debug_operation')) {
					\Cw::info([
						'当前状态'  => '字段检测',
						'是否允许'  => in_array($check_operation, $fieldOp),
						'当前字段'  => $key,
						'字段值'   => $objVal,
						'可允许操作' => $fieldOp,
					]);
				}
			}

		}

		return $fieldOp;
	}


	/**
	 * 获取 流程控制菜单
	 * @param string $type
	 * @return mixed|string
	 */
	protected static function operation($table) {
		// define file
		$file = app_path(self::PROGRESS_PATH . '/' . $table . '.php');

		$progress = LmFile::readPhp($file);
		if (isset($progress)) {
			return $progress;
		} else {
			return [];
		}
	}


}