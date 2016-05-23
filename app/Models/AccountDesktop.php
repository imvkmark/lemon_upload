<?php namespace App\Models;

/**
 * App\Models\AccountDesktop
 * @property integer                         $account_id 账户ID
 * @property string                          $mobile     手机号码
 * @property string                          $realname   真实姓名
 * @property string                          $qq
 * @property-read \App\Models\PamAccount     $pam
 * @property-read \App\Models\PamRoleAccount $role
 * @mixin \Eloquent
 */
class AccountDesktop extends \Eloquent {


	protected $table = 'account_desktop';

	protected $primaryKey = 'account_id';

	public $timestamps = false;

	protected $fillable = [
		'account_id',
		'mobile',
		'realname',
		'qq',
	];

	public function pam() {
		return $this->hasOne('App\Models\PamAccount', 'account_id');
	}

	public function role() {
		return $this->hasOne('App\Models\PamRoleAccount', 'account_id');
	}

}
