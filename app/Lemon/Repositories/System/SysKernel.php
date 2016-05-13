<?php namespace App\Lemon\Repositories\System;

/*
 * 定义 session, cookie 在系统中的变量
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 Sour Lemon Team
 */

class SysKernel {

	const SESSION_GO        = 'go';
	const SESSION_VALIDATED = 'validated';

	const FORM_TYPE_TEXT              = 'text';
	const FORM_TYPE_COLOR             = 'color';
	const FORM_TYPE_THUMB             = 'thumb';
	const FORM_TYPE_MULTI_IMAGE       = 'multi_image';
	const FORM_TYPE_TEXTAREA          = 'textarea';
	const FORM_TYPE_AREA              = 'area';
	const FORM_TYPE_CATEGORY          = 'category';
	const FORM_TYPE_BRAND             = 'brand';
	const FORM_TYPE_CATEGORY_PROPERTY = 'category_property';
	const FORM_TYPE_MULTI_CITY        = 'multi_city';
	const FORM_TYPE_DATE              = 'date';
	const FORM_TYPE_DATETIME          = 'datetime';
	const FORM_TYPE_MESSAGE           = 'message';
	const FORM_TYPE_MAP               = 'map';
	const FORM_TYPE_MULTI_SELECT      = 'multi_select';
	const FORM_TYPE_SINGLE_SELECT     = 'single_select';
	const FORM_TYPE_TYPE              = 'type';
	const FORM_TYPE_EDITOR            = 'editor';
	const FORM_TYPE_FILE              = 'file';
}