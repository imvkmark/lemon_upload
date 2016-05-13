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
	'_groups'                 => [

		'site'     => [
			'title'           => '网站控制',
			'first_col_class' => 'w180',
		],
		'transfer' => [
			'title'           => '转账',
			'first_col_class' => 'w180',
		],
		'order'   => [
			'title'           => '订单',
			'first_col_class' => 'w180',
		],
	],
	'site_name'               => [
		'form_type'    => SysKernel::FORM_TYPE_TEXT,
		'title'        => '网站名称',
		'validator'    => 'required',
		'group'        => 'site',
		'form_options' => [
			'class' => 'w240',
		],
	],
	'is_open'                 => [
		'form_type'                   => SysKernel::FORM_TYPE_SINGLE_SELECT,
		'title'                       => '站点开启',
		'single_select_default_value' => 0,
		'single_select_options'       => [
			'N' => '否',
			'Y' => '是',
		],
		'group'                       => 'site',
	],
	'close_reason'            => [
		'form_type'    => SysKernel::FORM_TYPE_TEXTAREA,
		'title'        => '站点关闭原因',
		'group'        => 'site',
		'form_options' => [
			'cols' => 30,
			'rows' => 5,
		],
	],
	'copyright'               => [
		'form_type'    => SysKernel::FORM_TYPE_TEXTAREA,
		'title'        => '版权信息',
		'group'        => 'site',
		'form_options' => [
			'cols' => 30,
			'rows' => 5,
		],
	],

	/*
	|--------------------------------------------------------------------------
	| 转账
	|--------------------------------------------------------------------------
	|
	*/
	'open_transfer'           => [
		'form_type'                   => SysKernel::FORM_TYPE_SINGLE_SELECT,
		'title'                       => '是否开启转账支付',
		'single_select_default_value' => 0,
		'single_select_options'       => [
			'N' => '否',
			'Y' => '是',
		],
		'group'                       => 'transfer',
	],
	'transfer_alipay_account' => [
		'form_type'    => SysKernel::FORM_TYPE_TEXT,
		'title'        => '支付宝转账账号',
		'validator'    => 'required',
		'group'        => 'transfer',
		'form_options' => [
			'class' => 'w240',
		],
	],
	'cash_bank_type'          => [
		'form_type'    => SysKernel::FORM_TYPE_TEXTAREA,
		'title'        => '提现银行卡类型',
		'group'        => 'transfer',
		'tip'          => '每行一个, 提现支持的银行卡或者账户的类型',
		'form_options' => [
			'cols' => 30,
			'rows' => 5,
		],
	],
	'cash_rate' => [
		'form_type'    => SysKernel::FORM_TYPE_TEXT,
		'title'        => '提现手续费(%)',
		'validator'    => 'required|digits_between:0,100',
		'group'        => 'transfer',
		'form_options' => [
			'class' => 'w240',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| 订单
	|--------------------------------------------------------------------------
	|
	*/
	'order_over_hour' => [
		'form_type'    => SysKernel::FORM_TYPE_TEXT,
		'title'        => '订单返款时间（小时）',
		'validator'    => 'required|digits_between:0,100',
		'group'        => 'order',
		'form_options' => [
			'class' => 'w120',
		],
	],

];