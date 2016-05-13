<?php namespace Imvkmark\SlUpload\Models;

use App\Lemon\Repositories\Sour\LmStr;

/**
 * This file is part of Sl Upload,
 * a upload management solution for Sour Lemon Framework.
 * @property integer                         $id
 * @property integer                         $account_id
 * @property string                          $key_public
 * @property string                          $key_type
 * @property string                          $key_secret
 * @property string                          $key_note
 * @property \Carbon\Carbon                  $created_at
 * @property \Carbon\Carbon                  $updated_at
 * @property-read \App\Models\PamAccount     $pam
 * @property-read \App\Models\PamRoleAccount $role
 * @mixin \Eloquent
 */
class SlImageKey extends \Eloquent {

	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table;

	/**
	 * Creates a new instance of the model.
	 * @param array $attributes
	 */
	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		$this->table = \Config::get('sl-upload.image_key_table');
	}


	protected $fillable = [
		'account_id',
		'key_public',
		'key_secret',
		'key_type',
		'key_note',
	];

	public function pam() {
		return $this->hasOne('App\Models\PamAccount', 'account_id');
	}

	public function role() {
		return $this->hasOne('App\Models\PamRoleAccount', 'account_id');
	}

	protected static $typeDesc = [
		'product' => '开发环境',
		'develop' => '测试环境',
	];

	public static function typeDesc($key) {
		if (isset(self::$typeDesc[$key])) {
			return self::$typeDesc[$key];
		} else {
			return '';
		}
	}

	public static function typeLinear() {
		return self::$typeDesc;
	}

	/**
	 * 获取开发者public ID
	 * @param $account_id
	 * @return string
	 */
	public static function genPublic($account_id) {
		// Prefix
		// Account
		// Random
		return sprintf("%s%08d%s", '33023', $account_id, LmStr::random(3, '0123456789'));
	}

	public static function publicExists($key_public) {
		return self::where('key_public', $key_public)->exists();
	}

	public static function getSecretByPublic($key_public) {
		return self::where('key_public', $key_public)->value('key_secret');
	}

	public static function getAccountIdByPublic($public) {
		return self::where('key_public', $public)->value('account_id');
	}
}
