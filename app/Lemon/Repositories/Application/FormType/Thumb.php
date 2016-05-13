<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Thumb extends Base implements FormTypeContract {

	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::thumb($this->name, $this->value, $this->form_options);
	}
}