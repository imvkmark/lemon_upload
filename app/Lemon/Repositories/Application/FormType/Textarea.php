<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Textarea extends Base implements FormTypeContract {


	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['textarea_default_value']) ? $setting['textarea_default_value'] : '';
		$this->placeholder                 = isset($setting['textarea_placeholder']) ? $setting['textarea_placeholder'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::textarea($this->name, $this->value, $this->form_options);
	}


}