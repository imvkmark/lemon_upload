<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class MultiSelect extends Base implements FormTypeContract {


	protected $multiOptions;

	public function __construct($name, $setting, $value = null, $raw_options = []) {
		parent::__construct($name, $setting, $value);
		$this->defaultValue   = isset($setting['multi_select_default_value']) ? $setting['multi_select_default_value'] : '';
		$multi_select_options = $setting['multi_select_options'];
		if (is_array($multi_select_options)) {
			$this->multiOptions = $multi_select_options;
		} elseif (is_string($multi_select_options)) {
			if (strpos($multi_select_options, '\\') === 0) {
				$this->multiOptions = call_user_func($multi_select_options);
			}
		}
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		return \Form::checkboxes($this->name.'[]', $this->multiOptions, $this->value, $this->form_options);
	}
}