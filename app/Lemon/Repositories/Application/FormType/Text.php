<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Text extends Base implements FormTypeContract {


	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['text_default_value']) ? $setting['text_default_value'] : '';
		$this->placeholder                 = isset($setting['text_placeholder']) ? $setting['text_placeholder'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::text($this->name, $this->value, $this->form_options);
	}

}