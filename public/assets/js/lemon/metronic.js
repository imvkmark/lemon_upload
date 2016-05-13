/**
 * 博客控制
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */
define(function (require, exports) {
	require('jquery');
	require('jquery.bt3');

	var app = require('lemon/metronic/app');
	var layout = require('lemon/metronic/layout');
	var demo = require('lemon/metronic/demo');
	var quick_sidebar = require('lemon/metronic/quick_sidebar');

	app.init();
	app.init_components();
	layout.init();
	demo.init();
	quick_sidebar.init();
});