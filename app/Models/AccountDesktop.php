<?php namespace App\Models;

/**
 * App\Models\AccountDesktop
 * @property integer                         $account_id 账户ID
 * @property string                          $mobile     手机号码
 * @property string                          $realname   真实姓名
 * @property string                          $qq
 * @property string                          $yi_account
 * @property string                          $yi_password
 * @property string                          $yi_payword
 * @property string                          $mao_account
 * @property string                          $mao_password
 * @property string                          $mao_payword
 * @property string                          $tong_account
 * @property string                          $tong_password
 * @property string                          $tong_payword
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
		'yi_account',
		'yi_password',
		'yi_payword',
		'mao_account',
		'mao_password',
		'mao_payword',
		'tong_account',
		'tong_password',
		'tong_payword',
	];

	public function pam() {
		return $this->hasOne('App\Models\PamAccount', 'account_id');
	}

	public function role() {
		return $this->hasOne('App\Models\PamRoleAccount', 'account_id');
	}

}
