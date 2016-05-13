/*
 * plugin
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 * @package
 */
// 插件提示
(function ($) {
	// for simple use from util.js
	$.browser = function () {
		var userAgent = navigator.userAgent.toLowerCase();
		return {
			version : (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [0, '0'])[1],
			safari : /webkit/.test(userAgent),
			opera : /opera/.test(userAgent),
			msie : /msie/.test(userAgent) && !/opera/.test(userAgent),
			mozilla : /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
		}
	}();

	$.fn.plugin_tooltip = function () {

		return this.each(function () {
			var text = $(this).attr("data-tip");
			if ( text != undefined ) {
				$(this).hover(function (e) {
					var tipX = e.pageX + 12;
					var tipY = e.pageY + 12;
					$("body").append("<div id='plugin_tooltip' class='sj-plugin_tooltip'>" + text + "</div>" + "<div id='plugin_tooltip-shadow'  class='sj-plugin_tooltip-shadow'>" + text + "</div>");
					var tipWidth;
					if ( $.browser.msie ) {
						tipWidth = $("#plugin_tooltip,#plugin_tooltip-shadow").outerWidth(true);
					} else {
						tipWidth = $("#plugin_tooltip,#plugin_tooltip-shadow").width();
					}
					$("#plugin_tooltip,#plugin_tooltip-shadow").width(tipWidth > 500 ? 500 : tipWidth);
					$("#plugin_tooltip").css("left", tipX).css("top", tipY).fadeIn("medium");
					$("#plugin_tooltip-shadow").css("left", tipX + 2).css("top", tipY + 2).fadeIn("medium");
				}, function () {
					$("#plugin_tooltip,#plugin_tooltip-shadow").remove();
					//$(this).attr("title", text);
				});
				$(this).mousemove(function (e) {
					var tipX = e.pageX + 12;
					var tipY = e.pageY + 12;
					var tipWidth = $("#plugin_tooltip,#plugin_tooltip-shadow").outerWidth(true);
					var tipHeight = $("#plugin_tooltip,#plugin_tooltip-shadow").outerHeight(true);
					if ( tipX + tipWidth > $(window).scrollLeft() + $(window).width() ) tipX = e.pageX - tipWidth;
					if ( $(window).height() + $(window).scrollTop() < tipY + tipHeight ) tipY = e.pageY - tipHeight;
					$("#plugin_tooltip").css("left", tipX).css("top", tipY).fadeIn("medium");
					$("#plugin_tooltip-shadow").css("left", tipX + 2).css("top", tipY + 2).fadeIn("medium");
				});
			}
		});
	};

	// 无缝滚动
	$.fn.plugin_scroll_seamless = function (options) {
		//默认配置
		var defaults = {
			speed : 40,  //滚动速度,值越大速度越慢
			rowHeight : 24 //每行的高度
		};

		var opts = $.extend({}, defaults, options), intId = [];

		function marquee(obj, step) {
			obj.find("ul").animate({
				marginTop : '-=1'
			}, 0, function () {
				var s = Math.abs(parseInt($(this).css("margin-top")));
				if ( s >= step ) {
					$(this).find("li").slice(0, 1).appendTo($(this));
					$(this).css("margin-top", 0);
				}
			});
		}

		this.each(function (i) {
			var sh = opts["rowHeight"], speed = opts["speed"], _this = $(this);
			intId[i] = setInterval(function () {
				if ( _this.find("ul").height() <= _this.height() ) {
					clearInterval(intId[i]);
				} else {
					marquee(_this, sh);
				}
			}, speed);

			_this.hover(function () {
				clearInterval(intId[i]);
			}, function () {
				intId[i] = setInterval(function () {
					if ( _this.find("ul").height() <= _this.height() ) {
						clearInterval(intId[i]);
					} else {
						marquee(_this, sh);
					}
				}, speed);
			});
		});
	}
})(jQuery);

// QQ 插件
window.online = window.online || '';
(function ($) {

	// plugin definition
	$.fn.plugin_qq = function (options) {
		var defaults = {
			qq : '732375676',
			qqDesc : '柠檬工作室'
		};
		// Extend our default options with those provided.
		var opts = $.extend(defaults, options);
		// Our plugin implementation code goes here.

		var arrQQ = opts.qq.split(',');
		var strQQ = arrQQ.join(':');
		var arrDesc = opts.qqDesc.split(',');
		var qqUrl = 'http://webpresence.qq.com/getonline?Type=1&' + strQQ + ':';
		var $this = $(this);
		$.getScript(qqUrl, function (data) {
			eval(data);
			var _html = '<div class="kefu-close"></div><div class="kefu-open"><div><ul>';
			for (var i = 0; i < arrQQ.length; i++) {
				_html += '<li><a href="http://wpa.qq.com/msgrd?v=3&uin=' + arrQQ[i] + '&site=qq&menu=yes" target="_blank"><i class="qq' + (online[i] ? online : '') + '"></i>' + arrDesc[i] + '</a></li>';
			}
			_html += '</ul></div><a href="javascript:;" class="close">关闭</a></div></div>';
			$this.append(_html);
			var _open = $this.find(".kefu-open"),
				_close = $this.find(".kefu-close");
			_open.find("a").hover(function () {
				$(this).stop(true, true).animate({paddingLeft : 20}, 200).find("i").stop(true, true).animate({left : 75}, 200);
			}, function () {
				$(this).stop(true, true).animate({paddingLeft : 35}, 200).find("i").stop(true, true).animate({left : 10}, 200);
			});

			_open.find(".close").click(function () {
				_open.animate({width : 148}, 200, function () {
					_open.animate({width : 0}, 200, function () {
						_close.animate({width : 34}, 200);

					});
				});
			});
			_close.click(function () {
				_close.animate({width : 44}, 200, function () {
					_close.animate({width : 0}, 200, function () {
						_open.animate({width : 138}, 200);
					});
				});
			});

		});

	};

})(jQuery);

// 左下工具, 交互
(function ($) {

	// plugin definition
	$.fn.plugin_rbmenu = function (options) {
		var defaults = {
			phone : '15254109156',
			qq : '408128151',
			wx : '',
			site : ''
		};
		// Extend our default options with those provided.
		var opts = $.extend(defaults, options);
		// Our plugin implementation code goes here.

		var $this = $(this);
		var tophtml = "<div class=\"sj-plugin_rbmenu\">";

		if ( opts.qq ) {
			tophtml += "<a href=\"tencent://Message/?Uin=" + opts.qq + "" +
				(opts.site ? "&websiteName=" + opts.site : '') +
				"&Menu=yes\" class=\"sj-btn btn-qq\"></a>";
		}
		if ( opts.wx ) {
			tophtml += "<div class=\"sj-btn btn-wx\"><img class=\"pic\" src=\"" + opts.wx + "\" " +
				(opts.site ? "onclick=\"window.location.href=\'" + opts.site + "'\"" : '') +
				"/></div>";
		}
		if ( opts.phone ) {
			tophtml += "<div class=\"sj-btn btn-phone\"><div class=\"phone\">" + opts.phone + "</div></div>";
		}
		tophtml += "<div class=\"sj-btn btn-top\"></div>";
		tophtml += "</div>";
		$this.append(tophtml);

		$this.find(".btn-wx").mouseenter(function () {
			$(this).find(".pic").fadeIn("fast");
		});
		$this.find(".btn-wx").mouseleave(function () {
			$(this).find(".pic").fadeOut("fast");
		});
		$this.find(".btn-phone").mouseenter(function () {
			$(this).find(".phone").fadeIn("fast");
		});
		$this.find(".btn-phone").mouseleave(function () {
			$(this).find(".phone").fadeOut("fast");
		});
		$this.find(".btn-top").click(function () {
			$("html, body").animate({
				"scroll-top" : 0
			}, "fast");
		});

		var lastRmenuStatus = false;
		$(window).scroll(function () {//bug
			var _top = $(window).scrollTop();
			if ( _top > 200 ) {
				$this.data("expanded", true);
			} else {
				$this.data("expanded", false);
			}
			if ( $this.data("expanded") != lastRmenuStatus ) {
				lastRmenuStatus = $this.data("expanded");
				if ( lastRmenuStatus ) {
					$this.find(".btn-top").slideDown();
				} else {
					$this.find(".btn-top").slideUp();
				}
			}
		});

	};

})(jQuery);