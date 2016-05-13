<?php namespace App\Lemon\Repositories\Application\FormType;

use App\Lemon\Repositories\Contracts\FormType as FormTypeContract;

class Map extends Base implements FormTypeContract {

	protected $formRelation;
	protected $posName;

	public function __construct($name, $setting, $value = null) {
		parent::__construct($name, $setting, $value);
		$this->formRelation = explode(',', $setting['form_relation']);
		$this->posName      = 'pos_name';
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render() {
		//return \Form::mapMarker($this->posName, $this->formRelation['0'], $this->formRelation['1'], $this->value, '');
	}
}