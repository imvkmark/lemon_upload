<?php namespace App\Models;


/**
 * App\Models\AccountValidate
 *
 * @property integer $valid_id 验证ID
 * @property string $valid_type 验证类型
 * @property string $valid_ip 发送验证的IP
 * @property string $valid_subject 邮箱或者手机号
 * @property string $valid_auth 保存的验证码值或者auth值
 * @property integer $account_id
 * @property string $expired_at 在什么时间过期
 * @property \Carbon\Carbon $created_at
 * @property string $deleted_at
 * @property \Carbon\Carbon $updated_at
 */
class AccountValidate extends \Eloquent {


	const VALID_STATUS_EXPIRED = 'expired';
	const VALID_STATUS_PASSED  = 'passed';
	const VALID_STATUS_WAIT    = 'mobile';

	const VALID_TYPE_EMAIL  = 'email';
	const VALID_TYPE_MOBILE = 'mobile';

	protected static $validTypeDesc = [
		self::VALID_TYPE_EMAIL  => '邮箱',
		self::VALID_TYPE_MOBILE => '手机',
	];

	protected static $validStatusDesc = [
		self::VALID_STATUS_EXPIRED => '已过期',
		self::VALID_STATUS_PASSED  => '通过验证',
		self::VALID_STATUS_WAIT    => '等待验证',
	];

	public    $timestamps = true;
	protected $table      = 'account_validate';
	protected $primaryKey = 'valid_id';
	protected $fillable   = [
		'valid_type',
		'valid_ip',
		'valid_subject',
		'valid_auth',
		'valid_status',
		'account_id',
		'expired_at',
	];



	public static function validTypeLinear() {
		return self::$validTypeDesc;
	}


	public static function validTypeDesc($key) {
		return isset(self::$validTypeDesc[$key]) ? self::$validTypeDesc[$key] : '';
	}

	public static function validStatusLinear() {
		return self::$validStatusDesc;
	}

	public static function validStatusDesc($key) {
		return isset(self::$validStatusDesc[$key]) ? self::$validStatusDesc[$key] : '';
	}

}
