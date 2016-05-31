<?php namespace App\Lemon\Repositories\Application;

use App\Lemon\Repositories\Contracts\FormType;
use App\Lemon\Repositories\System\SysKernel;
use App\Models\BaseModule;
use App\Models\ModuleField;

/**
 * 模块UI界面
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 Sour Lemon Team
 */
class ModuleUi {

	protected $formSetting  = [];
	protected $fieldSetting = [];
	protected $title        = '模块编辑';
	protected $moduleId;
	protected $url;


	public function __construct($module_id) {
		$this->moduleId = $module_id;
		$fields         = ModuleField::getCache($module_id);

		$formatFields = [];
		usort($fields, function ($v1, $v2) {
			return $v1['list_order'] > $v2['list_order'];
		});
		foreach ($fields as $k => $field) {
			if (!$field['form_type']) {
				unset($fields[$k]);
				continue;
			}

			if (!empty($field['form_relation'])) {
				$relations     = explode(',', $field['form_relation']);
				$formatKeys    = array_keys($formatFields);
				$intersectKeys = array_intersect($formatKeys, $relations);
				if ($intersectKeys) {
					unset($fields[$k]);
					continue;
				}
				$field['relations'] = $relations;
			}
			$field['title']                     = $field['field_title'];
			$field['module_id']                 = $module_id;
			$formatFields[$field['field_name']] = $field;
		}
		$this->fieldSetting = $formatFields;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function render($origin = []) {
		// 设置内容分组
		foreach ($this->fieldSetting as $setting_key => $setting) {

			$formType = $setting['form_type'];
			if($setting['field_name'])
			$class = '\\App\\Lemon\\Repositories\\Application\\FormType\\' . ucfirst(camel_case($formType));

			if (!isset($setting['relations'])) {
				if (is_object($origin)) {
					$current = isset($origin->$setting_key) ? $origin->$setting_key : '';
				} else {
					$current = isset($origin[$setting_key]) ? $origin[$setting_key] : null;
				}
			} else {
				$relations = $setting['relations'];
				$current   = [];
				foreach ($relations as $relation) {
					if (is_object($origin)) {
						$current[$relation] = isset($origin->$setting_key) ? $origin->$setting_key : '';
					} else {
						$current[$relation] = isset($origin[$setting_key]) ? $origin[$setting_key] : null;
					}
				}
			}

			/** @type FormType $object */
			$object                          = new $class($setting_key, $setting, $current);
			$setting['_render']              = $object->render();
			$setting['_label']               = in_array($formType,['map','area']) ? SysKernel::formTypeDesc($formType) : $object->label();
			$setting['_tip']                 = $object->tip();
			$this->formSetting[$setting_key] = $setting;
		}

		return view('lemon.module.desktop', [
			'title'     => $this->title,
			'url'       => $this->url,
			'module_id' => $this->moduleId,
			'module'    => BaseModule::getCache($this->moduleId),
			'fields'    => $this->formSetting,
			'modules'   => BaseModule::where('is_system', 0)->get(),
		]);
	}

	/**
	 * 前台图层渲染
	 * @param array $origin
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function front_render($origin = []){
		// 设置内容分组
		foreach ($this->fieldSetting as $setting_key => $setting) {
			$formType = $setting['form_type'];
			$class = '\\App\\Lemon\\Repositories\\Application\\FormType\\' . ucfirst(camel_case($formType));

			if (!isset($setting['relations'])) {
				if (is_object($origin)) {
					$current = isset($origin->$setting_key) ? $origin->$setting_key : '';
				} else {
					$current = isset($origin[$setting_key]) ? $origin[$setting_key] : null;
				}
			} else {
				$relations = $setting['relations'];
				$current   = [];
				foreach ($relations as $relation) {
					if (is_object($origin)) {
						$current[$relation] = isset($origin->$setting_key) ? $origin->$setting_key : '';
					} else {
						$current[$relation] = isset($origin[$setting_key]) ? $origin[$setting_key] : null;
					}
				}
			}

			/** @type FormType $object */
			$object                          = new $class($setting_key, $setting, $current);
			$setting['_render']              = $object->render();
			$setting['_label']               = $object->label();
			$setting['_tip']                 = $object->tip();
			$this->formSetting[$setting_key] = $setting;
		}

		return view('front.user.item', [
				'title'     => $this->title,
				'url'       => $this->url,
				'module_id' => $this->moduleId,
				'module'    => BaseModule::getCache($this->moduleId),
				'fields'    => $this->formSetting,
				'modules'   => BaseModule::where('is_system', 0)->get(),
		]);
	}
}