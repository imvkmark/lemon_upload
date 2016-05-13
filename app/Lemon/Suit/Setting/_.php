<?php
use App\Lemon\Repositories\Application\FormType\SingleSelect;
use \App\Lemon\Repositories\System\SysKernel;

/**
 * 根据 type/input_type 生成输入框, 默认是input 输入框, 同时根据type来获取验证类型, 所有类型不是必选输入, 因为存在默认值
 * 因为是设置项. 通过统一的入口来进入.
 * 能否通过 config入口进入?
 * 所有的配置项目不允许返回 null
 * 'default' // 默认值
 * 'title'   // 左侧标题说明
 * 'tip'     // 描述
 */

return [
	/*
	|--------------------------------------------------------------------------
	| 私有的配置使用 '_' 开头
	| 'sample' => [
	|	 'title'           => '网站控制',
	|	 'first_col_class' => 'w240',
	| ],
	|--------------------------------------------------------------------------
	*/
	'_groups'                    => [

		'site'    => [
			'title'           => '网站控制',
			'first_col_class' => 'w180',
		],
	],
	'text'                   => [
		'form_type'    => SysKernel::FORM_TYPE_TEXT,
		'title'        => '网站名称',
		'validator'    => 'required|digits_between:0,100|integer',
		'group'        => 'site',
		'form_options' => [
			'class' => 'w240',
		],
	],
	'textarea'                  => [
		'form_type'    => SysKernel::FORM_TYPE_TEXTAREA,
		'title'        => '版权信息',
		'validator'    => 'required',
		'group'        => 'site',
		'form_options' => [
			'cols' => 30,
			'rows' => 5,
		],
	],
	'radio'                      => [
		'form_type'                   => SysKernel::FORM_TYPE_SINGLE_SELECT,
		'title'                       => '站点开启',
		'single_select_default_value' => 0,
		'single_select_options'       => [
			1 => '否',
			0 => '是',
		],
		'group'                       => 'site',
	],
	'select'                   => [
		'form_type'             => SysKernel::FORM_TYPE_SINGLE_SELECT,
		'single_select_pc_type' => SingleSelect::PC_TYPE_SELECT,
		'title'                 => 'SEO 优化扩展名',
		'group'                 => 'seo',
		'single_select_options' => [
			'htm'   => 'htm',
			'html'  => 'html',
			'shtm'  => 'shtm',
			'shtml' => 'shtml',
		],
	],

];