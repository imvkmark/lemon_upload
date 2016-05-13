<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Category extends Base implements FormTypeContract {

	protected $moduleId = 0;

	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['category_default_value']) ? $setting['category_default_value'] : '';
		$this->placeholder                 = isset($setting['category_placeholder']) ? $setting['category_placeholder'] : '';
		$this->moduleId                    = isset($setting['module_id']) ? $setting['module_id'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::categoryLinkage($this->name, $this->moduleId, $this->value, $this->form_options);
	}
}