<?php namespace App\Models;

/**
 * App\Models\PamOnline
 *
 * @property integer $account_id
 * @property string  $login_ip IP
 * @property string  $logined_at
 */
class PamOnline extends \Eloquent {


	protected $table = 'pam_online';

	protected $primaryKey = 'account_id';

	protected $fillable   = [
		'account_id',
		'log_ip',
		'logined_at',
	];
	public    $timestamps = false;


}
