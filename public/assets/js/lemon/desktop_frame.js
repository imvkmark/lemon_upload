/*
 * 网站后台框架
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2016 Sour Lemon team
 */

define(function (require) {
	var $ = require("jquery"),
	util = require('lemon/util'),
	lemon = require('global');
	dialog = require('jquery.art-dialog');
	var handlebars = require('handlebars');

	var tpl = {
		sitemap : '{{#each root}}' +
		'<dl>' +
		'<dt>{{node_title}}</dt>' +
			//'{{#menus?}}'+
			//'<dt class="sub"><a href="javascript:void(0)">{{node_title}}</a></dt>'+
		'{{#menus}}' +
		'<dd><a href="javascript:void(0)" data-url="{{node_url}}">{{node_title}}</a></dd>' +
		'{{/menus}}' +
			//'{{/menus?}}'+
		'{{^menus}}' +
		'<dd><a href="javascript:void(0)" data-url="{{node_url}}">{{node_title}}</a></dd>' +
		'{{/menus}}' +
		'</dl>' +
		'{{/each}}'
	};

	// 工作区窗口自适应
	_autosizeWorkspace();
	$(window).resize(_autosizeWorkspace());

	// 左侧的菜单项目联动
	_bindSidebarDropdown();

	//iframe location
	if ( util.cookie('desktop#route') != null ) {
		_openItem(util.cookie('desktop#route'));
	} else {
		$('#J_mainMenu>ul').first().css('display', 'block');
		//第一次进入后台时，默认定到欢迎界面

		var $item = $('a[data-route="' + lemon.url_site + '/dsk_home/welcome' + '"]');

		var route = $item.attr("data-route");
		var rel = $item.attr("data-rel");
		if ( route ) _openItem(route, rel);
	}

	// 刷新管理中心
	$('#J_iframeRefresh').click(function () {
		var fr = document.frames ? document.frames("workspace") : document.getElementById("workspace").contentWindow;
		fr.location.reload();
	});

	//右上角小工具
	$('#J_quickAction').click(function () {
		$('ul.bar-list').toggle('fast');
	});

	// bookmark
	util.add_fav('#J_addBookmark', $(this).attr('data-label'), $(this).attr('data-linkurl'));
	//管理地图

	if ( typeof window.desktop_sitemap != 'undefined' &&
		typeof window.desktop_sitemap.root != 'undefined' &&
		window.desktop_sitemap.root.length != 0 ) {
		$("#J_sitemap").on('click', function () {
			window.desktop_dialog = dialog(util.dialog_conf({
				title : '网站地图',
				content : util.compile(tpl.sitemap, window.desktop_sitemap)
			}, 'desktop'));
			window.desktop_dialog.showModal();
		});
	} else {
		$("#J_sitemap").parents('.sitemap').remove();
	}

	// data-url 的链接跳转,支持对自定义生成dialog的链接
	$('body').on('click', 'a[data-route]', function () {
		var url = $(this).attr("data-route");
		var rel = $(this).attr("data-rel");
		_openItem(url, rel);
		// 对dialog 的父元素的处理， 代码比较清晰
		if ( $(this).parents('.sj-dialog-desktop').length > 0 ) {
			// 通用dialog 关闭
			window.desktop_dialog.close();
		}
	});

	// 自动缩放工作区域
	function _autosizeWorkspace() {
		var iframe = $("#workspace");
		var h = $(window).height() - iframe.offset().top;
		var w = $(window).width() - iframe.offset().left;
		if ( h < 300 ) h = 300;
		if ( w < 973 ) w = 973;
		iframe.height(h);
		iframe.width(w);
	}

	// 侧边绑定小导航分组切换特效
	function _bindSidebarDropdown() {
		$(".J_sideGroup").click(function () {
			var key = $(this).attr('data-rel');
			if ( $(this).attr("data-mark") == 'true' ) {
				$("[data-group=" + key + "]").slideDown("fast");
				$(this).find('dt').css("background-position", "-322px -170px");
				$(this).attr("data-mark", 'false')
			} else {
				$("[data-group=" + key + "]").slideUp("fast");
				$(this).find('dt').css("background-position", "-483px -170px");
				$(this).attr("data-mark", 'true')
			}
		});
	}

	// 条目链接url 以及联动
	function _openItem(route, rel) {

		if ( !rel ) {
			rel = $('a[data-route="' + route + '"]').attr('data-rel');
		}
		// top nav
		$('#J_nav a').removeClass('actived');
		$('#J_nav_' + rel).addClass('actived');

		// side display
		$('#J_mainMenu ul').css('display', 'none');
		$('#J_menu_' + rel).css('display', 'block');

		// side style
		$('.selected').removeClass('selected');
		$('a[data-route="' + route + '"]').addClass('selected');

		// record
		util.cookie('desktop#route', route);

		//crumbs
		$('#J_crumbs').html('<span>' + $('#J_nav_' + rel + ' > span').html() + '</span><span class="arrow">&nbsp;</span><span>' + $('a[data-route="' + route + '"]').html() + '</span>');

		var param = $('a[data-route="' + route + '"]').attr('data-param');
		if ( param ) {
			route += '?' + param;
		}
		// location
		$('#workspace').attr('src', route);
	}

});