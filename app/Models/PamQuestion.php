<?php namespace App\Models;


/**
 * App\Models\PamQuestion
 *
 * @property integer        $qst_id     id
 * @property string         $qst_title  安全验证问题
 * @property integer        $list_order 排序
 * @property string         $is_enable
 * @property \Carbon\Carbon $created_at
 * @property string         $deleted_at
 * @property \Carbon\Carbon $updated_at
 */
class PamQuestion extends \Eloquent {


	protected $table = 'pam_question';

	protected $primaryKey = 'qst_id';
	protected $fillable   = [
		'qst_title',
		'is_enable',
	];

	/**
	 * 获取所有question
	 * @return array
	 */
	public static function getLinear() {
		$questions = PamQuestion::where('is_enable', 'Y')->get();
		$return    = [];
		if ($questions) {
			foreach ($questions as $qst) {
				$return[$qst['qst_id']] = $qst['qst_title'];
			}
		}
		return $return;
	}
}
