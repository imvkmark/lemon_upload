<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class CategoryProperty extends Base implements FormTypeContract {

	protected $type = 0;

	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['category_property_default_value']) ? $setting['category_property_default_value'] : '';
		$this->placeholder                 = isset($setting['category_property_placeholder']) ? $setting['category_property_placeholder'] : '';
		$this->type                        = isset($setting['category_property_type']) ? $setting['category_property_type'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::categoryPropertyLinkage($this->name, $this->type, $this->value, $this->form_options);
	}
}