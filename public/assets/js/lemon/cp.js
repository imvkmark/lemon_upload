/**
 * 控制面板
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2016 lemon team
 */
define(function (require) {
	var $ = require('jquery'),
		moment = require('moment'),
		dialog = require('jquery.art-dialog'),
		util = require('lemon/util');
	require('jquery.form');

	$(function () {
		// 对话框, 用于显示信息提示
		$('.J_dialog').on('click', function (e) {
			// confirm
			var tip = $(this).attr('data-tip');
			var title = $(this).attr('data-title') ? $(this).attr('data-title') : $(this).html();
			var width = parseInt($(this).attr('data-width')) ? parseInt($(this).attr('data-width')) : 400;
			var height = parseInt($(this).attr('data-height')) ? parseInt($(this).attr('data-height')) : '';

			var settings = {
				title : title,
				content : tip,
				fixed : true
			};

			width ? settings = $.extend(settings, {width : width}) : '';
			height ? settings = $.extend(settings, {height : height}) : '';

			// do request
			window.dialog = dialog(util.dialog_conf(settings, 'desktop'));
			window.dialog.showModal();
			e.preventDefault();
		});

		// 弹出 iframe url
		$('a.J_iframe').on('click', function (e) {
			var $this = $(this);
			// confirm
			var href = $(this).attr('href');
			var title = $(this).attr('data-title') ? $(this).attr('data-title') : $(this).html();
			var width = parseInt($(this).attr('data-width')) ? parseInt($(this).attr('data-width')) : 500;
			var height = parseInt($(this).attr('data-height'));
			var append = $this.attr('data-append');
			var data = util.append_to_obj(append);
			href = util.obj_to_url(data, href);
			var settings = {
				title : title,
				url : href,
				fixed : true,
				width : width
			};

			height ? settings = $.extend(settings, {height : height}) : '';

			// do request
			window.iframe = dialog(util.dialog_conf(settings, 'desktop'));
			window.iframe.showModal();
			e.preventDefault();
		});

		// 全选 start
		$('.J_checkAll, .J_check_all').on('click change', function () {
			if ( this.checked ) {
				$(".J_checkItem, .J_check_item").prop('checked', true)
			} else {
				$(".J_checkItem, .J_check_item").prop('checked', false)
			}
		});

		// 确定 请求后台操作, POST 方法
		$('.J_request').on('click', function (e) {
			util.request_event($(this), util.splash_front);
			e.preventDefault();
		});

		// 图片预览
		$('.J_image_preview').on('click', function (e) {
			var _src = $(this).attr('src');
			if ( _src.indexOf('nopic') >= 0 ) {
				return;
			}
			if ( e.ctrlKey ) {
				window.open($(this).attr('src'), '_blank')
			} else {
				util.image_popup_show($(this).attr('src'), $(window).width() / 2);
			}
		});

		// reload
		$('.J_reload').on('click', function () {
			window.location.reload();
		});

		/**
		 * .J_submit  用法
		 * data-url    : 设置本表单请求的URL
		 * data-ajax   : true|false  设置是否进行ajax 请求
		 * data-confirm: 确认操作提交的提示信息
		 */
		$('.J_submit').on('click', function () {
			var _url = $(this).attr('data-url');
			if ( !_url ) {
				util.splash({
					'status' : 'error',
					'msg' : '当前请求没有设置请求的URI'
				});
				return false;
			}
			// confirm
			var str_confirm = $(this).attr('data-confirm');
			if ( str_confirm && !confirm(str_confirm) ) return false;

			var data_ajax = $(this).attr('data-ajax');

			var $form = $(this).parents('form');

			if ( (data_ajax == 'true') ) {
				$form.attr('action', _url);
				$form.ajaxSubmit({
					success : util.splash
				});
			} else {
				$form.attr('action', _url);
				$form.submit();
			}
		});

		moment.locale('zh-cn');
		$('.J_timeago').each(function () {
			var time_str = $(this).text();
			if ( moment(time_str, "YYYY-MM-DD HH:mm:ss", true).isValid() ) {
				$(this).text(moment(time_str).fromNow());
			}
		});

		// 确定 ajax删除 的操作
		$('a.J_delete').on('click', function (e) {
			// confirm
			var str_confirm = $(this).attr('data-confirm');
			str_confirm = str_confirm ? str_confirm : '您确定要删除吗?';
			if ( !confirm(str_confirm) ) return false;

			// do request
			var href = $(this).attr('href');
			var token = $('meta[name="csrf-token"]').attr('content');
			$.post(href, {
				_method : 'DELETE',
				_token : token
			}, util.splash);
			e.preventDefault();
		});
	});
});