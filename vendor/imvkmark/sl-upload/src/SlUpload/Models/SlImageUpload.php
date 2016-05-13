<?php namespace Imvkmark\SlUpload\Models;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 * @license MIT
 * @package Zizaco\Entrust
 * @property integer        $id
 * @property integer        $account_id
 * @property string         $upload_path
 * @property string         $upload_extension
 * @property integer        $upload_filesize
 * @property string         $upload_mime
 * @property string         $upload_field
 * @property integer        $upload_width
 * @property integer        $upload_height
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class SlImageUpload extends Model {

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
		$this->table = \Config::get('sl-upload.image_upload_table');
	}

	protected $fillable = [
		'upload_path',
		'upload_type',
		'upload_extension',
		'upload_filesize',
		'upload_mime',
		'image_type',
		'image_width',
		'image_height',
		'account_id',
	];

}
