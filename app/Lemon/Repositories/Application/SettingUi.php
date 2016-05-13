<?php namespace App\Lemon\Repositories\Application;

use App\Lemon\Repositories\Contracts\FormType;
use \App\Lemon\Repositories\Sour\LmFile;
use \App\Lemon\Repositories\System\SysKernel;

/**
 * 用户UI界面
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 Sour Lemon Team
 */
class SettingUi {

	protected $dir         = '';
	protected $file        = '';
	protected $viewPrefix  = '';
	protected $deContent   = [];
	protected $groups      = [];
	protected $formSetting = [];
	protected $title       = '默认设置项';
	protected $type;
	protected $url;
	private   $desktop     = false;

	public function __construct($type) {
		$this->type       = $type;
		$this->dir        = lemon_path('Suit/Setting');
		$this->file       = $this->dir . '/' . $this->type . '.php';
		$this->viewPrefix = 'lemon.setting.' . camel_case($type) . '.';
		if (!file_exists($this->file)) {
			throw new \Exception('设置文件' . $this->file . '不存在!');
		}
		$this->deContent = LmFile::readPhp($this->file);
		if (isset($this->deContent['_groups'])) {
			$this->groups = $this->deContent['_groups'];
			unset($this->deContent['_groups']);
		}
		$this->formSetting = $this->deContent;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function setDesktop() {
		$this->desktop = true;
	}

	/**
	 * 获取设置信息
	 * @param $key
	 * @return string
	 */
	public function getDefaultValue($key) {
		if (isset($this->formSetting[$key])) {
			$class = '\\App\\Lemon\\Repositories\\Application\\FormType\\' . ucfirst(camel_case($this->formSetting[$key]['form_type']));
			/** @type FormType $object */
			$object = new $class($key, $this->formSetting[$key]);
			return $object->defaultValue();
		} else {
			return '';
		}
	}

	public function render($origin) {
		// 设置内容分组
		foreach ($this->formSetting as $setting_key => $setting) {

			$formType = $setting['form_type'];

			$class = '\\App\\Lemon\\Repositories\\Application\\FormType\\' . ucfirst(camel_case($formType));

			$current_key = $setting_key;
			if (str_contains($setting_key, '[')) {
				$current_key = str_replace(['[', ']'], ['.', ''], $setting_key);
			}
			$current = array_get($origin, $current_key);
			if ($this->desktop) {
				if (!isset($setting['form_options'])) {
					$setting['form_options'] = [];
				}
				$setting['form_options']['desktop'] = 'true';
			}

			// for file type
			if ($setting['form_type'] == SysKernel::FORM_TYPE_FILE) {
				$setting['view_prefix'] = $this->viewPrefix;
			}
			/** @type FormType $object */
			$object             = new $class($setting_key, $setting, $current);
			$setting['_render'] = $object->render();
			$setting['_label']  = $object->label();
			$setting['_tip']    = $object->tip();
			if (isset($setting['group']) && $setting['group'] && isset($this->groups[$setting['group']])) {
				$this->groups[$setting['group']]['_items'][$setting_key] = $setting;
			} else {
				$this->groups['_other']['_items'][$setting_key] = $setting;
			}
		}
		return view('lemon.setting.desktop', [
			'title'  => $this->title,
			'url'    => $this->url,
			'type'   => $this->type,
			'groups' => $this->groups,
		]);
	}
}