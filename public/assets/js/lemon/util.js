define(function (require, exports) {
	var $ = require('jquery'),
		dialog = require('jquery.art-dialog'),
		toastr = require('jquery.toastr'),
		classie = require('classie'),
		lemon = require('global');
	require('jquery.form');
	require('jquery.poshytip');
	require('lemon/plugin');

	//浏览器判定

	/**
	 *
	 * @type {{version, safari, opera, msie, mozilla}}
	 */
	exports.browser = function () {
		var userAgent = navigator.userAgent.toLowerCase();
		return {
			version : (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [0, '0'])[1],
			safari : /webkit/.test(userAgent),
			opera : /opera/.test(userAgent),
			msie : /msie/.test(userAgent) && !/opera/.test(userAgent),
			mozilla : /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent),
			is_ie8 : !!userAgent.match(/msie 8.0/),
			is_ie9 : !!userAgent.match(/msie 9.0/),
			is_ie10 : !!userAgent.match(/msie 10.0/),
			is_rtl : $('body').css('direction') === 'rtl'
		}
	};

	exports.validate_conf = function (settings, conf_type) {
		if ( typeof conf_type == 'undefined' || conf_type == '' || conf_type == 'default' ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			// 默认是使用 ajax 提交的, 需要 加载 form 插件
			var conf_default = {
				submitHandler : function (form) {
					$(form).ajaxSubmit({
						success : exports.splash
					});
				},
				success : function (label) {
					label.addClass('valid');
				},
				onfocusout : function (element) {
					$(element).valid();
				},
				onfocusin : function (element) {
					$(element).valid();
				}
			};
			return $.extend(conf_default, settings);
		}
		if ( typeof conf_type != 'undefined' && conf_type == 'form' ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_form = {
				success : function (label) {
					label.addClass('valid');
				},
				onfocusout : function (element) {
					$(element).valid();
				},
				onfocusin : function (element) {
					$(element).valid();
				}
			};
			return $.extend(conf_form, settings);
		}
		if ( typeof conf_type != 'undefined' && (conf_type == 'bt3' || conf_type == 'bootstrap') ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_bt3 = {
				//success : function (label) {
				//	label.addClass('valid');
				//},
				highlight : function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight : function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function (error, element) {
					if ( element.parent('.input-group').length ) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
				//validClass: 'has-success',
				//onfocusout : function (element) {
				//	$(element).valid();
				//},
				//onfocusin : function (element) {
				//	$(element).valid();
				//}
			};
			return $.extend(conf_bt3, settings);
		}
		if ( typeof conf_type != 'undefined' && conf_type == 'bt3_ajax' ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_bt3_ajax = {
				submitHandler : function (form) {
					$(form).ajaxSubmit({
						success : exports.splash
					});
				},
				highlight : function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight : function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function (error, element) {
					if ( element.parent('.input-group').length ) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			};
			return $.extend(conf_bt3_ajax, settings);
		}
		if ( typeof conf_type != 'undefined' && (conf_type == 'bt3_inline' || conf_type == 'bootstrap_inline') ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_bootstrap_inline = {
				//success : function (label) {
				//	label.addClass('valid');
				//},
				highlight : function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight : function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function (error, element) {
					if ( $(element).closest('.form-group').find('.help-block').length == 0 ) {
						$(element).closest('.form-group').append(error);
						error.insertAfter();
					}
				}
				//validClass: 'has-success',
				//onfocusout : function (element) {
				//	$(element).valid();
				//},
				//onfocusin : function (element) {
				//	$(element).valid();
				//}
			};
			return $.extend(conf_bootstrap_inline, settings);
		}

		if ( typeof conf_type != 'undefined' && (conf_type == 'bt3_self' || conf_type == 'bootstrap_self') ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_bootstrap_self = {
				highlight : function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight : function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function (error, element) {
					$(element).prop('placeholder', error.text())
				}
			};
			return $.extend(conf_bootstrap_self, settings);
		}
		if ( typeof conf_type != 'undefined' && (conf_type == 'bt3_self_ajax') ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_bt3_self_ajax = {
				submitHandler : function (form) {
					$(form).ajaxSubmit({
						success : exports.splash
					});
				},
				highlight : function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight : function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function (error, element) {
					$(element).prop('placeholder', error.text())
				}
			};
			return $.extend(conf_bt3_self_ajax, settings);
		}
		if ( typeof conf_type != 'undefined' && (conf_type == 'bt3_metronic' || conf_type == 'bt3_ajax_tip') ) {
			settings = (typeof settings == 'undefined') ? {} : settings;
			var conf_bt3_metronic_ajax = {
				submitHandler : function (form) {
					$(form).ajaxSubmit({
						success : exports.splash
					});
				},
				errorPlacement : function (error, element) {
					$(element).plugin_validate_tip(error.text());
				}
			};
			return $.extend(conf_bt3_metronic_ajax, settings);
		}
	};

	exports.dialog_conf = function (settings, conf_type) {
		if ( typeof conf_type == 'undefined' || conf_type == '' || conf_type == 'default' ) {
			var conf_default = {};
			return $.extend(conf_default, settings);
		}
		if ( conf_type == 'desktop' ) {
			var conf_admin = {
				skin : 'sj-dialog-desktop'
			};
			return $.extend(conf_admin, settings);
		}
	};

	// 加入收藏
	exports.add_fav = function (id) {
		$(id).click(function () {
			if ( document.all ) {
				try {
					window.external.addFavorite(window.location.href, document.title);
				} catch (e) {
					alert("加入收藏失败，请使用Ctrl+D进行添加");
				}
			} else if ( window.sidebar ) {
				window.sidebar.addPanel(document.title, window.location.href, "");
			} else {
				alert("加入收藏失败，请使用Ctrl+D进行添加");
			}
		})
	};

	exports.scroll_show = function (_sel, sep_top) {
		var $_sel = $(_sel);
		sep_top = typeof sep_top != 'undefined' ? sep_top : 0;
		$(window).scroll(function () {
			if ( (document.documentElement.scrollTop + document.body.scrollTop) > sep_top ) {
				$_sel.show();
			} else {
				$_sel.hide();
			}
		})
	};

	/**
	 * cbpAnimatedHeader.min.js v1.0.0
	 * http://www.codrops.com
	 *
	 * Licensed under the MIT license.
	 * http://www.opensource.org/licenses/mit-license.php
	 *
	 * Copyright 2013, Codrops
	 * http://www.codrops.com
	 */
	exports.scroll_switch = function (selector, sep_top, class_name) {
		var doc_element = document.documentElement, query_selector = document.querySelector(selector), markable = false, offset_location = sep_top;

		function go() {
			window.addEventListener("scroll", function (h) {
				if ( !markable ) {
					markable = true;
					setTimeout(do_listen, 250)
				}
			}, false)
		}

		function do_listen() {
			var height = offset_y();
			if ( height >= offset_location ) {
				classie.add(query_selector, class_name)
			} else {
				classie.remove(query_selector, class_name)
			}
			markable = false
		}

		function offset_y() {
			return window.pageYOffset || doc_element.scrollTop
		}

		go()
	};

	//判断是否为图片
	exports.is_image = function (url) {
		var sTemp;
		var b = false;
		var opt = "jpg|gif|png|bmp|jpeg";
		var s = opt.toUpperCase().split("|");
		for (var i = 0; i < s.length; i++) {
			sTemp = url.substr(url.length - s[i].length - 1);
			sTemp = sTemp.toUpperCase();
			s[i] = "." + s[i];
			if ( s[i] == sTemp ) {
				b = true;
				break;
			}
		}
		return b;
	};

	exports.is_email = function (str) {
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
		return reg.test(str);
	};

	/**
	 * 手机号码
	 * @param str
	 * @returns {boolean|Array|{index: number, input: string}}
	 */
	exports.is_mobile = function (str) {
		var phone_number = str.replace(/\(|\)|\s+|-/g, "");
		return phone_number.length > 9 && phone_number.match(/^1[3|4|5|8|7][0-9]\d{4,8}$/);
	};

	exports.is_swf = function (url) {
		var sTemp;
		var b = false;
		var opt = "swf";
		var s = opt.toUpperCase().split("|");
		for (var i = 0; i < s.length; i++) {
			sTemp = url.substr(url.length - s[i].length - 1);
			sTemp = sTemp.toUpperCase();
			s[i] = "." + s[i];
			if ( s[i] == sTemp ) {
				b = true;
				break;
			}
		}
		return b;
	};

	// util.scale_image($('.J_img_scale'), 50);
	exports.scale_image = function (_sel, w, h) {
		if ( typeof h == 'undefined' ) {
			h = w;
		}
		$(_sel).each(function (i, ele) {
			if ( $(ele).width() > $(ele).height() ) {
				$(ele).css('width', w + 'px');
				$(ele).css('height', 'auto');
			} else {
				$(ele).css('height', h + 'px');
				$(ele).css('width', 'auto');
			}
		})
	};

	/**
	 * 计算图片的大小
	 * @param sUrl
	 * @param fCallback
	 */
	exports.image_size = function (sUrl, fCallback) {
		var img = new Image();
		img.src = sUrl + '?t=' + Math.random();    //IE下，ajax会缓存，导致onreadystatechange函数没有被触发，所以需要加一个随机数
		if ( exports.browser.msie ) {
			img.onreadystatechange = function () {
				if ( this.readyState == "loaded" || this.readyState == "complete" ) {
					fCallback({width : img.width, height : img.height, url : sUrl});
				}
			};
		} else if ( exports.browser().mozilla || exports.browser().safari || exports.browser().opera ) {
			img.onload = function () {
				fCallback({width : img.width, height : img.height, url : sUrl});
			};
		} else {
			fCallback({width : img.width, height : img.height, url : sUrl});
		}
	};

	/**
	 * 图片头数据加载就绪事件 - 更快获取图片尺寸
	 * @version 2011.05.27
	 * @author  TangBin
	 * @see   http://www.planeart.cn/?p=1121
	 * @param {String}  图片路径
	 * @param {Function}  尺寸就绪
	 * @param {Function}  加载完毕 (可选)
	 * @param {Function}  加载错误 (可选)
	 * @example util.image_size('http://www.google.com.hk/intl/zh-CN/images/logo_cn.png', function () {
          alert('size ready: width=' + this.width + '; height=' + this.height);
       });
	 */
	exports.return_image_size = function () {
		var list = [], intervalId = null,

		// 用来执行队列
			tick = function () {
				var i = 0;
				for (; i < list.length; i++) {
					list[i].end ? list.splice(i--, 1) : list[i]();
				}
				;
				!list.length && stop();
			},

		// 停止所有定时器队列
			stop = function () {
				clearInterval(intervalId);
				intervalId = null;
			};

		return function (url, ready, load, error) {
			var onready, width, height, newWidth, newHeight,
				img = new Image();

			img.src = url;

			// 如果图片被缓存，则直接返回缓存数据
			if ( img.complete ) {
				ready.call(img);
				load && load.call(img);
				return;
			}
			;

			width = img.width;
			height = img.height;

			// 加载错误后的事件
			img.onerror = function () {
				error && error.call(img);
				onready.end = true;
				img = img.onload = img.onerror = null;
			};

			// 图片尺寸就绪
			onready = function () {
				newWidth = img.width;
				newHeight = img.height;
				if ( newWidth !== width || newHeight !== height ||
					// 如果图片已经在其他地方加载可使用面积检测
					newWidth * newHeight > 1024
				) {
					ready.call(img);
					onready.end = true;
				}
				;
			};
			onready();

			// 完全加载完毕的事件
			img.onload = function () {
				// onload在定时器时间差范围内可能比onready快
				// 这里进行检查并保证onready优先执行
				!onready.end && onready();

				load && load.call(img);

				// IE gif动画会循环执行onload，置空onload即可
				img = img.onload = img.onerror = null;
			};

			// 加入队列中定期执行
			if ( !onready.end ) {
				list.push(onready);
				// 无论何时只允许出现一个定时器，减少浏览器性能损耗
				if ( intervalId === null ) intervalId = setInterval(tick, 40);
			}
		};
	}();

	exports.go = function (param, value, tripFile) {
		var stringObj = window.location.href.replace(/#/, '');
		var params;
		typeof tripFile == 'undefined' ? tripFile = window.location.pathname : tripFile;
		var lstr = "&";

		if ( stringObj.indexOf(tripFile + '?') == -1 ) {
			lstr = "?";
		}
		if ( param.indexOf('|') >= 0 ) {
			params = param.split('|')
		} else {
			params = [param];
		}
		var urlGo = stringObj;
		for (var i in params) {
			var param_re = params[i];
			var reg = new RegExp(param_re + "=[0-9a-zA-Z,-_]*", "g"); //创建正则RegExp对象
			var ch = stringObj.indexOf(param_re + '=');
			if ( ch == -1 ) {
				urlGo += lstr + param_re + "=" + value;
			}
			if ( ch != -1 ) {
				urlGo = urlGo.replace(reg, param_re + "=" + value);
			}

		}
		window.location = urlGo;
	};

	exports.set_homepage = function () {
		if ( document.all ) {
			document.body.style.behavior = 'url(#default#homepage)';
			document.body.setHomePage(window.location.href);
		} else if ( window.sidebar ) {
			if ( window.netscape ) {
				try {
					netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
				} catch (e) {
					alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true");
				}
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage', window.location.href);
		} else {
			alert('您的浏览器不支持自动自动设置首页, 请使用浏览器菜单手动设置!');
		}
	};

	// 异步读取
	exports.make_request = function (targetPhp, queryString, success) {
		$.ajax({
			async : false,
			cache : false,
			type : 'post',
			url : targetPhp,
			data : queryString,
			success : success
		});
	};

	// jsonp获取
	exports.make_jsonp = function (targetPhp, queryString, success) {
		$.getJSON(targetPhp + '&callback=?', queryString, success);
	};

	/**
	 * 生成随机字符
	 * @param length
	 * @returns {string}
	 */
	exports.random = function (length) {
		if ( typeof length == 'undefined' || parseInt(length) == 0 ) {
			length = 18;
		}
		var chars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ";
		var str = '';
		for (var i = 0; i < length; i++) {
			str += chars.charAt(Math.floor(Math.random() * chars.length));
		}
		return str;
	};

	/**
	 * 项目cookie
	 * @param name
	 * @param value
	 * @param opt
	 * @returns {*|The}
	 */
	exports.cookie = function (name, value, opt) {
		var defaults = {
			path : lemon.cookie_path,
			domain : lemon.cookie_domain
		};
		var opts = $.extend(defaults, opt);
		return exports.cookie_raw(lemon.cookie_prefix + name, value, opts);
	};

	/**
	 * Get the value of a cookie with the given name.
	 *
	 * @example $.cookie_raw('the_cookie');
	 * @desc Get the value of a cookie.
	 * @type String
	 * @cat Plugins/Cookie
	 * @author Klaus Hartl/klaus.hartl@stilbuero.de
	 * @param name
	 * @param value
	 * @param options
	 * @return The value of the cookie.
	 */
	exports.cookie_raw = function (name, value, options) {
		if ( typeof value != 'undefined' ) { // name and value given, set cookie
			options = options || {};
			if ( value === null ) {
				value = '';
				options.expires = -1;
			}
			var expires = '';
			if ( options.expires && (typeof options.expires == 'number' || options.expires.toUTCString) ) {
				var date;
				if ( typeof options.expires == 'number' ) {
					date = new Date();
					date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
				} else {
					date = options.expires;
				}
				expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
			}
			var path = options.path ? '; path=' + options.path : '';
			var domain = options.domain ? '; domain=' + options.domain : '';
			var secure = options.secure ? '; secure' : '';
			document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
		} else { // only name given, get cookie
			var cookieValue = null;
			if ( document.cookie && document.cookie != '' ) {
				var cookies = document.cookie.split(';');
				for (var i = 0; i < cookies.length; i++) {
					var cookie = $.trim(cookies[i]);
					// Does this cookie string begin with the name we want?
					if ( cookie.substring(0, name.length + 1) == (name + '=') ) {
						cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
						break;
					}
				}
			}
			return cookieValue;
		}
	};

	exports.classie = function () {
		function classReg(className) {
			return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
		}

		// classList support for class management
		// altho to be fair, the api sucks because it won't accept multiple classes at once
		var hasClass, addClass, removeClass;

		if ( 'classList' in document.documentElement ) {
			hasClass = function (elem, c) {
				return elem.classList.contains(c);
			};
			addClass = function (elem, c) {
				elem.classList.add(c);
			};
			removeClass = function (elem, c) {
				elem.classList.remove(c);
			};
		}
		else {
			hasClass = function (elem, c) {
				return classReg(c).test(elem.className);
			};
			addClass = function (elem, c) {
				if ( !hasClass(elem, c) ) {
					elem.className = elem.className + ' ' + c;
				}
			};
			removeClass = function (elem, c) {
				elem.className = elem.className.replace(classReg(c), ' ');
			};
		}

		function toggleClass(elem, c) {
			var fn = hasClass(elem, c) ? removeClass : addClass;
			fn(elem, c);
		}

		return {
			// full names
			hasClass : hasClass,
			addClass : addClass,
			removeClass : removeClass,
			toggleClass : toggleClass,
			// short names
			has : hasClass,
			add : addClass,
			remove : removeClass,
			toggle : toggleClass
		};
	};

	/**
	 * 大图显示对应缩略图
	 * @param big    大图列表ul Id
	 * @param small  缩略图列表ul Id
	 */
	exports.show_thumb = function (big, small) {
		var $bigImg = $(big).find("li");
		$(small).find("li:first").children("i").show();
		$(small).find("li").each(function (index, element) {
			$(this).hover(function () {
				$(this).children("i").show();
				$(this).siblings().children("i").hide();
				$bigImg.eq(index).show().siblings().hide();
			});
		});
	};

	exports.obj_to_url = function (obj, url) {
		var str = "";
		for (var key in obj) {
			if ( str != "" ) {
				str += "&";
			}
			str += key + "=" + obj[key];
		}
		if ( typeof url != 'undefined' ) {
			return url.indexOf('?') >= 0 ? url + '&' + str : url + '?' + str;
		} else {
			return str;
		}
	};

	/**
	 * 计算对象的长度
	 * @param obj
	 * @returns {number}
	 */
	exports.obj_size = function (obj) {
		var count = 0;

		if ( typeof obj == "object" ) {

			if ( Object.keys ) {
				count = Object.keys(obj).length;
			} else if ( window._ ) {
				count = _.keys(obj).length;
			} else if ( window.$ ) {
				count = $.map(obj, function () {
					return 1;
				}).length;
			} else {
				for (var key in obj) if ( obj.hasOwnProperty(key) ) count++;
			}

		}

		return count;
	};

	/*
	 * 提示信息
	 * @params word  String 提示信息
	 * */
	exports.splash = function (resp) {
		var obj_resp = exports.to_json(resp);
		var obj_init = {
			time : 0,
			msg : 'No Message Send By Server!',
			status : 'error',
			callback : '',
			show : 'tip',
			tip_callback : 'toastr'
		};

		obj_resp = $.extend(obj_init, obj_resp);

		if ( obj_resp.show == 'tip' && obj_resp.msg && (obj_resp.status == 'error' || obj_resp.status == 'success') ) {
			obj_resp.time = parseInt(obj_resp.time) ? parseInt(obj_resp.time) : 0;
			if ( obj_resp.tip_callback == 'tip' ) {
				exports.tip(obj_resp);
			}
			if ( obj_resp.tip_callback == 'toastr') {
				if (obj_resp.location) {
					var jump_time = 800;
				}
				setTimeout(function () {
					toastr.options = {
						closeButton : true,
						progressBar : true,
						showMethod : 'slideDown',
						timeOut : 4000
					};
					if ( obj_resp.status == 'error' ) {
						toastr.error(obj_resp.msg);
					}
					if ( obj_resp.status == 'success' ) {
						toastr.success(obj_resp.msg);
					}
				}, jump_time);
			}

		}

		if ( obj_resp.show == 'dialog' && obj_resp.msg && (obj_resp.status == 'error' || obj_resp.status == 'success') ) {
			delete obj_resp.show;
			var dialog_conf = '';
			var conf;
			if ( obj_resp.hasOwnProperty('dialog_conf') ) {
				dialog_conf = obj_resp.dialog_conf;
			}
			conf = exports.dialog_conf(obj_resp, dialog_conf);
			conf.title = !conf.hasOwnProperty('title') ? conf.msg : conf.title;
			conf.content = !conf.hasOwnProperty('content') ? conf.msg : conf.content;
			dialog(conf).show();
			return false;
		}

		if ( obj_resp.status == 'callback' || obj_resp.callback ) {
			var func = obj_resp.callback;
			setTimeout(function () {
				eval(func + ";");
			}, obj_init.time);
		}

		if ( obj_resp.reload ) {
			setTimeout(function () {
				window.location.reload()
			}, obj_init.time);
			return;
		}

		if ( obj_resp.location ) {
			setTimeout(function () {
				window.location.href = obj_resp.location;
			}, obj_init.time);
		}

		if ( obj_resp.reload_opener ) {
			setTimeout(function () {
				var opener = exports.opener(obj_resp.reload_opener);
				opener.location.reload();
			}, obj_init.time);
		}
	};

	// 验证显示错误的位置- splash
	exports.splash_desktop = function (attr, event) {
		var error = '';
		for (var i in attr) {
			if ( !error ) {
				error += attr[i]
			}
		}
		if ( error ) {
			exports.splash({
				status : 'error',
				msg : error
			});
		}
	};

	exports.splash_front = function (resp) {
		var obj_resp = exports.to_json(resp);
		obj_resp.tip_callback = 'toastr';
		exports.splash(obj_resp);
	};

	exports.append_to_obj = function (append) {
		var data = {};
		if ( append ) {
			var appends = [append];
			if ( append.indexOf(',') >= 0 ) {
				appends = append.split(',');
			}
			for (var i in appends) {
				var item = appends[i];
				var re = /(.*)\((.*)\)/;
				var m;

				if ( (m = re.exec(item)) !== null ) {
					if ( m.index === re.lastIndex ) {
						re.lastIndex++;
					}
				}

				if ( m[1].indexOf('checked') >= 0 && m[1].indexOf('radio') < 0 ) {
					var id_array = [];
					$(m[1]).each(function () {
						id_array.push($(this).val());//向数组中添加元素
					});
					data[m[2]] = id_array;//将数组元素连接起来以构建一个字符串
				} else {
					data[m[2]] = $(m[1]).val();
				}

			}
		}
		return data;
	};

	exports.condition_to_obj = function (append) {
		var data = {};
		if ( append ) {
			var appends = append.split(',');
			for (var i in appends) {
				var item = appends[i];
				var re = /(.*):(.*)/;
				var m;
				if ( (m = re.exec(item)) !== null ) {
					if ( m.index === re.lastIndex ) {
						re.lastIndex++;
					}
					data[m[1]] = m[2];
				}
			}
		}
		return data;
	};

	/**
	 * objResp.msg
	 * objResp.status
	 * objResp.time
	 * @param objResp
	 */
	exports.tip = function (objResp) {
		var _tem = '<div id="splash" class="J_splash">' +
			'<span id="splash_content"></span>' +
			'</div>';

		// remove
		if ( $('#splash').length ) {
			$('#splash').remove();
		}

		$("body").append($(_tem));
		$('#splash_content').html(objResp.msg).removeClass().addClass(objResp.status);

		var $splash_content = $('#splash_content');
		var marginLeft = ($splash_content.width() + 20) / 2;
		$splash_content.css({'margin-left' : -marginLeft + "px", top : top});
		// $('#J_tip').delay(objResp.time).animate({top : -100}, 500);
		$('#splash').delay(objResp.time).fadeOut(3000);
		/*
		 var marginTop = ($J_tipContent.height() + 20) / 2;
		 $J_tipContent.css({'margin-left' : -marginLeft + "px", 'margin-top' : -marginTop+'px'});
		 $('#J_tip').delay(objResp.time).fadeOut(500);
		 */
	};

	exports.refresh = function () {
		top.window.location.reload();
	};

	// opener
	exports.opener = function (workspace) {
		var opener = top.frames[workspace];
		if ( typeof opener == 'undefined' ) {
			opener = top;
		}
		return opener;
	};

	exports.reload_captcha = function (id) {
		$(id).trigger('click');
	};

	/**
	 *
	 * @param btn_selector
	 * @param data
	 * @param error_submit
	 */
	exports.button_interaction = function (btn_selector, data, error_submit) {
		var objData;
		if ( typeof data == 'undefined' || !isNaN(parseInt(data)) ) {
			$(btn_selector).attr('disabled', true);
			if ( !isNaN(parseInt(data)) ) {
				var time = parseInt(data);
				setTimeout(function () {
					$(btn_selector).attr('disabled', false);
				}, time * 1000);
			}
		}
		objData = exports.to_json(data);
		if ( objData.status == 'error' ) {
			$(btn_selector).attr('disabled', false);
			if ( typeof error_submit != 'undefined' ) {
				$(btn_selector).html(error_submit);
			}
		}
	};

	/**
	 * 字串转 json
	 * @param resp
	 * @returns {*}
	 */
	exports.to_json = function (resp) {
		var objResp;
		if ( typeof resp == 'object' ) {
			objResp = resp;
		} else {
			if ( $.trim(resp) == '' ) {
				objResp = {};
			} else {
				objResp = $.parseJSON(resp);
			}
		}
		return objResp;
	};

	/**
	 * 按钮倒计时工具
	 * @param btn_selector
	 * @param str
	 * @param time
	 * @param end_str
	 */
	exports.countdown = function (btn_selector, str, time, end_str) {
		var count = time;
		var handlerCountdown;
		var $btn = $(btn_selector);
		var displayStr = typeof end_str != 'undefined' ? end_str : $btn.text();

		handlerCountdown = setInterval(_countdown, 1000);
		$btn.attr("disabled", true);

		function _countdown() {
			var count_str = str.replace(/\{time\}/, count);
			$btn.text(count_str);
			if ( count == 0 ) {
				$btn.text(displayStr).removeAttr("disabled");
				clearInterval(handlerCountdown);
			}
			count--;
		}
	};

	/**
	 * 事件请求, 使用post 方法
	 * @param $this
	 * @param splash_func
	 * @returns {boolean}
	 */
	exports.request_event = function ($this, splash_func) {
		// confirm
		var str_confirm = $this.attr('data-confirm');
		if ( str_confirm ) {
			if ( !confirm(str_confirm) ) return false;
		}
		var append = $this.attr('data-append');
		var data = exports.append_to_obj(append);

		var condition_str = $this.attr('data-condition');
		var condition = exports.condition_to_obj(condition_str);
		for (var i in data) {
			if ( condition.hasOwnProperty(i) && !data.hasOwnProperty(i) ) {
				splash_func({
					'status' : 'error',
					'msg' : condition[i]
				});
				return false;
			}
		}

		// do request
		var href = $this.attr('href');
		data._token = $('meta[name="csrf-token"]').attr('content');
		$.post(href, data, splash_func);
	};

	/**
	 * 预览图像地址
	 * @param imgSrc
	 * @param w
	 * @returns {boolean}
	 */
	exports.image_popup_show = function (imgSrc, w) {
		if ( !imgSrc ) {
			exports.splash('error', '没有图像文件!');
			return false;
		}
		exports.image_size(imgSrc, _popup_show);
		/**
		 * imgObj.width   imgObj.height  imgObj.url
		 * @param imgObj
		 * @private
		 */
		function _popup_show(imgObj) {
			var _w = imgObj.width;
			var _h = imgObj.height;
			if ( typeof w != 'undefined' && imgObj.width > w ) {
				_w = w;
				_h = parseInt(_w * imgObj.height / imgObj.width);
			}
			var imgStr = '<img src="' + imgObj.url + '" width="' + _w + '" height="' + _h + '" />';
			dialog({
				title : '图片预览',
				content : imgStr
			}).showModal();
		}

	};

	exports.image_hover_show = function (ctr, imgUrl, w) {
		if ( typeof imgUrl == 'undefined' || !imgUrl ) {
			exports.splash('error', '没有图像文件!');
			return false;
		}
		exports.image_size(imgUrl, _previewShow);

		/**
		 * imgObj.width   imgObj.height  imgObj.url
		 * @param imgObj
		 * @private
		 */
		function _previewShow(imgObj) {
			var _w = imgObj.width;
			var _h = imgObj.height;
			if ( typeof w != 'undefined' && imgObj.width > w ) {
				_w = w;
				_h = parseInt(_w * imgObj.height / imgObj.width);
			}

			var imgStr = '<div id="J_previewShow" class="file-element"><div class="file-preview" style="left: 110px;top:0;">' +
				'<img src="' + imgObj.url + '" width="' + _w + '" height="' + _h + '" />' +
				'</div>';
			$('#J_previewShow').remove();
			$(ctr).append(imgStr);
			$('#J_previewShow>.file-preview').show();
		}
	};

	exports.image_hover_hide = function () {
		$('#J_previewShow').remove();
	};

	/**
	 * To get the correct viewport width
	 * based on  http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
	 * @returns {{width: *, height: *}}
	 */
	exports.get_viewport = function () {
		var e = window,
			a = 'inner';
		if ( !('innerWidth' in window) ) {
			a = 'client';
			e = document.documentElement || document.body;
		}

		return {
			width : e[a + 'Width'],
			height : e[a + 'Height']
		};
	};

	/**
	 * 是否是触摸设备
	 * check for device touch support
	 * @returns {boolean}
	 */
	exports.is_touch_device = function () {
		try {
			document.createEvent("TouchEvent");
			return true;
		} catch (e) {
			return false;
		}
	};

	/**
	 * 获取唯一ID
	 * @param prefix
	 * @returns {string}
	 */
	exports.get_unique_id = function (prefix) {
		var _pre = (typeof prefix == 'undefined') ? 'prefix_' : prefix;
		return _pre + Math.floor(Math.random() * (new Date()).getTime());
	};

	//public function to get a paremeter by name from URL
	exports.get_url_parameter = function (paramName) {
		var searchString = window.location.search.substring(1),
			i, val, params = searchString.split("&");

		for (i = 0; i < params.length; i++) {
			val = params[i].split("=");
			if ( val[0] == paramName ) {
				return unescape(val[1]);
			}
		}
		return '';
	};

	// check if browser support HTML5 local storage
	exports.local_storage_support = function () {
		return (('localStorage' in window) && window['localStorage'] !== null)
	};
});