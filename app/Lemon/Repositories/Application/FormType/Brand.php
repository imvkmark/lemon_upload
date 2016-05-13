<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Brand extends Base implements FormTypeContract {

	protected $formRelation;
	protected $brandIdInput;
	protected $brandNameInput;

	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$relation       = explode(',', $setting['form_relation']);
		$brandIdInput   = '';
		$brandNameInput = '';
		foreach ($relation as $input) {
			switch ($input) {
				case (strpos($input, 'brand_id') !== false && !$brandIdInput):
					$brandIdInput = $input;
					break;
				case (strpos($input, 'brand_name') !== false && !$brandNameInput):
					$brandNameInput = $input;
					break;
			}
		}
		$this->brandIdInput   = $brandIdInput;
		$this->brandNameInput = $brandNameInput;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		$brandIdValue   = isset($this->value[$this->brandIdInput]) ? $this->value[$this->brandIdInput] : '';
		$brandNameValue = isset($this->value[$this->brandNameInput]) ? $this->value[$this->brandNameInput] : '';
		return \Form::brand($this->brandIdInput, $this->brandNameInput, $brandIdValue, $brandNameValue);
	}
}