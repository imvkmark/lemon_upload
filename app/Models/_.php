<?php namespace App\Models;


/**
 * App\Models\_
 *
 * @property-read \App\Models\PamAccount $pam
 */
class _ extends \Eloquent {

	protected $table = 'table_name';

	protected $primaryKey  = 'id';

	public $timestamps = true;

	protected $fillable = [
		'account_id',
		'amount',
		'balance',
		'editor_id',
		'note',
	];

	public function pam() {
		return $this->belongsTo('App\Models\PamAccount', 'account_id', 'account_id');
	}
}
