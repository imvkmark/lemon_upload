<?php namespace App\Models;


/**
 * App\Models\AccountDevelop
 *
 * @property integer $account_id 账户id
 * @property string $truename 联系人姓名
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property-read \App\Models\PamAccount $pam
 */
class AccountDevelop extends \Eloquent {


	protected $table = 'account_develop';

	protected $primaryKey = 'account_id';

	public $timestamps = false;

	protected $fillable = [
		'account_id',
		'truename',
		'nickname',
		'email',
	];

	public function pam() {
		return $this->hasOne('App\Models\PamAccount', 'account_id');
	}
}
