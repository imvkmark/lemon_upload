/*
 * controll panel 控制面板
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @version    $Id$
 * @package
 */
define(function (require) {
	var $ = require("jquery"),
		util = require('lemon/util'),
		dialog = require('jquery.art-dialog');
	require("lemon/plugin");
	require("lemon/cp");
	require('jquery.tools');

	// 后端提示
	if ( typeof util.cookie('desktop.splash') != 'undefined' && util.cookie('desktop.splash') != null ) {
		var data = util.cookie('desktop.splash');
		var splash = $.parseJSON(data);
		util.splash(splash.msg, splash.status);
		util.cookie('desktop.splash', null);
	}

	// tooltip
	$("[data-tip]").plugin_tooltip();

	// tab

	$('.J_tab').each(function (i, ele) {
		var $ele = $(ele);
		var tab = util.cookie('desktop.tab');
		var rel = $ele.attr('data-relation');
		var idx = $ele.attr('data-current');
		var cfg = {};
		if ( tab ) {
			$ele.find('a[data-rel]').find('[data-rel=' + tab + ']').index();
			cfg = {initialIndex : idx}
		}
		$ele.tabs('[data-rel=' + rel + ']', cfg);
	});

	//下拉列表
	$('img[nc_type="flex"]').click(function () {
		var status = $(this).attr('status');
		if ( status == 'open' ) {
			$(this).attr('status', 'none');
			$(".row" + $(this).attr('fieldid')).show();
			$(this).attr('src', $(this).attr('src').replace("tv-expandable", "tv-collapsable"));
			$(this).attr('status', 'close');
		}
		if ( status == 'close' ) {
			$(".row" + $(this).attr('fieldid')).hide();
			$(this).attr('src', $(this).attr('src').replace("tv-collapsable", "tv-expandable"));
			$(this).attr('status', 'open');
		}
	});

	//自定义radio样式
	$(".cb-onoff>.enable").click(function () {
		var parent = $(this).parents('.cb-onoff');
		$('.disable', parent).removeClass('selected');
		$(this).addClass('selected');
		$('.J_onoffEnabled', parent).attr('checked', true);
	});
	$(".cb-onoff>.disable").click(function () {
		var parent = $(this).parents('.cb-onoff');
		$('.enable', parent).removeClass('selected');
		$(this).addClass('selected');
		$('.J_onoffEnabled', parent).attr('checked', false);
	});

	// 表格鼠标悬停变色 start
	$(".J_hover tr").hover(function () {
		$(this).css({background : "#FBFBFB"});
	}, function () {
		$(this).css({background : "#FFF"});
	});

	// 提示操作 展开与隐藏
	$("#J_prompt tr:odd").addClass("odd");
	$("#J_prompt tr:not(.odd)").hide();
	$("#J_prompt tr:first-child").show();

	$("#J_prompt tr.odd").click(function () {
		$(this).next("tr").toggle();
		$(this).find(".title").toggleClass("ac");
		$(this).find(".arrow").toggleClass("up");
	});

	// 模拟网站LOGO上传input type='file'样式
	// 上传图片类型
	$('.file-element input[class="file-file"]').on("change", function () {
		var filepatd = $(this).val();
		var extStart = filepatd.lastIndexOf(".");
		var ext = filepatd.substring(extStart, filepatd).toUpperCase();
		if ( ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG" ) {
			alert("图片限于png,gif,jpeg,jpg格式");
			$(this).attr('value', '');
			return false;
		} else {
			$(this).siblings(".file-text").val(filepatd);
		}
	});

	// 页码跳转
	$('#page_button').on('click', function () {
		var page;
		var $page_location = $('#page_location');
		if ( isNaN(parseInt($page_location.val())) ) {
			page = 0;
		} else {
			page = parseInt($page_location.val());
		}
		if ( !page ) {
			util.splash({
				'status': 'error',
				'msg' : '不正确的页码数值'
			});
		} else {
			util.go('p', page);
		}
	})

});
