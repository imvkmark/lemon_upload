<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Area extends Base implements FormTypeContract {

	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['area_default_value']) ? $setting['area_default_value'] : '';
		$this->placeholder                 = isset($setting['area_placeholder']) ? $setting['area_placeholder'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::areaLinkage($this->name, $this->value, $this->form_options);
	}
}