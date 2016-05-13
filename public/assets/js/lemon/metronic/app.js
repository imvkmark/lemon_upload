/**
 * 博客控制
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */
define(function (require, exports) {

	var $ = require('jquery');
	var jQuery = $;
	var toastr = require('jquery.toastr');
	require('jquery.bt3');

	var util = require('lemon/util');

	/**
	 Core script to handle the entire theme and core functions
	 **/

	// IE mode
	var _is_rtl = false;
	var _is_ie8 = false;
	var _is_ie9 = false;
	var _is_ie10 = false;

	// 更改大小时候的处理器
	var _resize_handlers = [];

	// theme layout color set

	var brandColors = {
		'blue' : '#89C4F4',
		'red' : '#F3565D',
		'green' : '#1bbc9b',
		'purple' : '#9b59b6',
		'grey' : '#95a5a6',
		'yellow' : '#F8CB00'
	};

	// initializes main settings, 初始化主要设置
	var _handle_init = function () {
		var browser = util.browser();
		if ( browser.is_rtl ) {
			_is_rtl = true;
		}

		_is_ie8 = browser.is_ie8;
		_is_ie9 = browser.is_ie9;
		_is_ie10 = browser.is_ie10;

		if ( _is_ie10 ) {
			$('html').addClass('ie10'); // detect IE10 version
		}

		if ( _is_ie10 || _is_ie9 || _is_ie8 ) {
			$('html').addClass('ie'); // detect IE10 version
		}
	};

	// runs callback functions set by App.addResponsiveHandler().
	var _run_resize_handlers = function () {
		// reinitialize other subscribed elements
		for (var i = 0; i < _resize_handlers.length; i++) {
			var each = _resize_handlers[i];
			each.call();
		}
	};

	// handle the layout reinitialization on window resize
	var _handle_on_resize = function () {
		var resize;
		if ( _is_ie8 ) {
			var currheight;
			$(window).resize(function () {
				if ( currheight == document.documentElement.clientHeight ) {
					return; //quite event since only body resized not window.
				}
				if ( resize ) {
					clearTimeout(resize);
				}
				resize = setTimeout(function () {
					_run_resize_handlers();
				}, 50); // wait 50ms until window resize finishes.
				currheight = document.documentElement.clientHeight; // store last body client height
			});
		} else {
			$(window).resize(function () {
				if ( resize ) {
					clearTimeout(resize);
				}
				resize = setTimeout(function () {
					_run_resize_handlers();
				}, 50); // wait 50ms until window resize finishes.
			});
		}
	};

	// Handles portlet tools & actions, 组件工具和动作
	var _handle_portlet_tools = function () {
		// handle portlet remove
		$('body').on('click', '.portlet > .portlet-title > .tools > a.remove', function (e) {
			e.preventDefault();
			var portlet = $(this).closest(".portlet");

			if ( $('body').hasClass('page-portlet-fullscreen') ) {
				$('body').removeClass('page-portlet-fullscreen');
			}

			portlet.find('.portlet-title .fullscreen').tooltip('destroy');
			portlet.find('.portlet-title > .tools > .reload').tooltip('destroy');
			portlet.find('.portlet-title > .tools > .remove').tooltip('destroy');
			portlet.find('.portlet-title > .tools > .config').tooltip('destroy');
			portlet.find('.portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip('destroy');

			portlet.remove();
		});

		// handle portlet fullscreen
		$('body').on('click', '.portlet > .portlet-title .fullscreen', function (e) {
			e.preventDefault();
			var portlet = $(this).closest(".portlet");
			if ( portlet.hasClass('portlet-fullscreen') ) {
				$(this).removeClass('on');
				portlet.removeClass('portlet-fullscreen');
				$('body').removeClass('page-portlet-fullscreen');
				portlet.children('.portlet-body').css('height', 'auto');
			} else {
				var height = util.get_viewport().height -
					portlet.children('.portlet-title').outerHeight() -
					parseInt(portlet.children('.portlet-body').css('padding-top')) -
					parseInt(portlet.children('.portlet-body').css('padding-bottom'));

				$(this).addClass('on');
				portlet.addClass('portlet-fullscreen');
				$('body').addClass('page-portlet-fullscreen');
				portlet.children('.portlet-body').css('height', height);
			}
		});

		$('body').on('click', '.portlet > .portlet-title > .tools > a.reload', function (e) {
			e.preventDefault();
			var el = $(this).closest(".portlet").children(".portlet-body");
			var url = $(this).attr("data-url");
			var error = $(this).attr("data-error-display");
			if ( url ) {
				exports.block_ui({
					target : el,
					animate : true,
					overlayColor : 'none'
				});
				$.ajax({
					type : "GET",
					cache : false,
					url : url,
					dataType : "html",
					success : function (res) {
						exports.unblock_ui(el);
						el.html(res);
						exports.init_ajax(); // reinitialize elements & plugins for newly loaded content
					},
					error : function (xhr, ajaxOptions, thrownError) {
						exports.unblock_ui(el);
						var msg = 'Error on reloading the content. Please check your connection and try again.';
						if ( error == "toastr" && toastr ) {
							toastr.error(msg);
						} else if ( error == "notific8" && $.notific8 ) {
							$.notific8('zindex', 11500);
							$.notific8(msg, {
								theme : 'ruby',
								life : 3000
							});
						} else {
							alert(msg);
						}
					}
				});
			} else {
				// for demo purpose
				exports.block_ui({
					target : el,
					animate : true,
					overlayColor : 'none'
				});
				window.setTimeout(function () {
					exports.unblock_ui(el);
				}, 1000);
			}
		});

		// load ajax data on page init
		$('.portlet .portlet-title a.reload[data-load="true"]').click();

		$('body').on('click', '.portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand', function (e) {
			e.preventDefault();
			var el = $(this).closest(".portlet").children(".portlet-body");
			if ( $(this).hasClass("collapse") ) {
				$(this).removeClass("collapse").addClass("expand");
				el.slideUp(200);
			} else {
				$(this).removeClass("expand").addClass("collapse");
				el.slideDown(200);
			}
		});
	};

	// Handles custom checkboxes & radios using jQuery Uniform plugin
	// 自定义 checkbox 和 radio
	require('jquery.uniform');
	var _handle_uniform = function () {
		if ( !$().uniform ) {
			return;
		}
		var test = $("input[type=checkbox]:not(.toggle, .md-check, .md-radiobtn, .make-switch, .icheck), input[type=radio]:not(.toggle, .md-check, .md-radiobtn, .star, .make-switch, .icheck)");
		if ( test.size() > 0 ) {
			test.each(function () {
				if ( $(this).parents(".checker").size() === 0 ) {
					$(this).show();
					$(this).uniform();
				}
			});
		}
	};

	// Handlesmaterial design checkboxes
	var _handle_material_design = function () {

		// Material design ckeckbox and radio effects
		$('body').on('click', '.md-checkbox > label, .md-radio > label', function () {
			var the = $(this);
			// find the first span which is our circle/bubble
			var el = $(this).children('span:first-child');

			// add the bubble class (we do this so it doesnt show on page load)
			el.addClass('inc');

			// clone it
			var newone = el.clone(true);

			// add the cloned version before our original
			el.before(newone);

			// remove the original so that it is ready to run on next click
			$("." + el.attr("class") + ":last", the).remove();
		});

		if ( $('body').hasClass('page-md') ) {
			// Material design click effect
			// credit where credit's due; http://thecodeplayer.com/walkthrough/ripple-click-effect-google-material-design
			var element, circle, d, x, y;
			$('body').on('click', 'a.btn, button.btn, input.btn, label.btn', function (e) {
				element = $(this);

				if ( element.find(".md-click-circle").length == 0 ) {
					element.prepend("<span class='md-click-circle'></span>");
				}

				circle = element.find(".md-click-circle");
				circle.removeClass("md-click-animate");

				if ( !circle.height() && !circle.width() ) {
					d = Math.max(element.outerWidth(), element.outerHeight());
					circle.css({height : d, width : d});
				}

				x = e.pageX - element.offset().left - circle.width() / 2;
				y = e.pageY - element.offset().top - circle.height() / 2;

				circle.css({top : y + 'px', left : x + 'px'}).addClass("md-click-animate");

				setTimeout(function () {
					circle.remove();
				}, 1000);
			});
		}

		// Floating labels
		var _handle_input = function (el) {
			if ( el.val() != "" ) {
				el.addClass('edited');
			} else {
				el.removeClass('edited');
			}
		}

		$('body').on('keydown', '.form-md-floating-label .form-control', function (e) {
			_handle_input($(this));
		});
		$('body').on('blur', '.form-md-floating-label .form-control', function (e) {
			_handle_input($(this));
		});

		$('.form-md-floating-label .form-control').each(function () {
			if ( $(this).val().length > 0 ) {
				$(this).addClass('edited');
			}
		});
	}

	// Handles custom checkboxes & radios using jQuery iCheck plugin
	require('jquery.icheck');
	var _handle_iCheck = function () {
		if ( !$().iCheck ) {
			return;
		}

		$('.icheck').each(function () {
			var checkboxClass = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-grey';
			var radioClass = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-grey';

			if ( checkboxClass.indexOf('_line') > -1 || radioClass.indexOf('_line') > -1 ) {
				$(this).iCheck({
					checkboxClass : checkboxClass,
					radioClass : radioClass,
					insert : '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
				});
			} else {
				$(this).iCheck({
					checkboxClass : checkboxClass,
					radioClass : radioClass
				});
			}
		});
	};

	// Handles Bootstrap switches
	var _handle_bootstrap_switch = function () {
		if ( !$().bootstrapSwitch ) {
			return;
		}
		$('.make-switch').bootstrapSwitch();
	};

	// Handles Bootstrap confirmations
	var _handle_bootstrap_confirmation = function () {
		if ( !$().confirmation ) {
			return;
		}
		$('[data-toggle=confirmation]').confirmation({container : 'body', btnOkClass : 'btn btn-sm btn-success', btnCancelClass : 'btn btn-sm btn-danger'});
	};

	// Handles Bootstrap Accordions.
	var _handle_accordions = function () {
		$('body').on('shown.bs.collapse', '.accordion.scrollable', function (e) {
			exports.scrollTo($(e.target));
		});
	};

	// Handles Bootstrap Tabs.
	var _handle_tabs = function () {
		//activate tab if tab id provided in the URL
		if ( location.hash ) {
			var tabid = encodeURI(location.hash.substr(1));
			$('a[href="#' + tabid + '"]').parents('.tab-pane:hidden').each(function () {
				var tabid = $(this).attr("id");
				$('a[href="#' + tabid + '"]').click();
			});
			$('a[href="#' + tabid + '"]').click();
		}

		if ( $().tabdrop ) {
			$('.tabbable-tabdrop .nav-pills, .tabbable-tabdrop .nav-tabs').tabdrop({
				text : '<i class="fa fa-ellipsis-v"></i>&nbsp;<i class="fa fa-angle-down"></i>'
			});
		}
	};

	// Handles Bootstrap Modals.
	var _handle_modals = function () {
		// fix stackable modal issue: when 2 or more modals opened, closing one of modal will remove .modal-open class.
		$('body').on('hide.bs.modal', function () {
			if ( $('.modal:visible').size() > 1 && $('html').hasClass('modal-open') === false ) {
				$('html').addClass('modal-open');
			} else if ( $('.modal:visible').size() <= 1 ) {
				$('html').removeClass('modal-open');
			}
		});

		// fix page scrollbars issue
		$('body').on('show.bs.modal', '.modal', function () {
			if ( $(this).hasClass("modal-scroll") ) {
				$('body').addClass("modal-open-noscroll");
			}
		});

		// fix page scrollbars issue
		$('body').on('hide.bs.modal', '.modal', function () {
			$('body').removeClass("modal-open-noscroll");
		});

		// remove ajax content and remove cache on modal closed
		$('body').on('hidden.bs.modal', '.modal:not(.modal-cached)', function () {
			$(this).removeData('bs.modal');
		});
	};

	// Handles Bootstrap Tooltips.
	var _handle_tooltips = function () {
		// global tooltips
		$('.tooltips').tooltip();

		// portlet tooltips
		$('.portlet > .portlet-title .fullscreen').tooltip({
			container : 'body',
			title : 'Fullscreen'
		});
		$('.portlet > .portlet-title > .tools > .reload').tooltip({
			container : 'body',
			title : 'Reload'
		});
		$('.portlet > .portlet-title > .tools > .remove').tooltip({
			container : 'body',
			title : 'Remove'
		});
		$('.portlet > .portlet-title > .tools > .config').tooltip({
			container : 'body',
			title : 'Settings'
		});
		$('.portlet > .portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip({
			container : 'body',
			title : 'Collapse/Expand'
		});
	};

	// Handles Bootstrap Dropdowns
	var _handle_dropdowns = function () {
		/*
		 Hold dropdown on click
		 */
		$('body').on('click', '.dropdown-menu.hold-on-click', function (e) {
			e.stopPropagation();
		});
	};

	var _handle_alerts = function () {
		$('body').on('click', '[data-close="alert"]', function (e) {
			$(this).parent('.alert').hide();
			$(this).closest('.note').hide();
			e.preventDefault();
		});

		$('body').on('click', '[data-close="note"]', function (e) {
			$(this).closest('.note').hide();
			e.preventDefault();
		});

		$('body').on('click', '[data-remove="note"]', function (e) {
			$(this).closest('.note').remove();
			e.preventDefault();
		});
	};

	// Handle Hower Dropdowns
	require('jquery.bt3.hover-dropdown');
	var _handle_dropdown_hover = function () {
		$('[data-hover="dropdown"]').not('.hover-initialized').each(function () {
			$(this).dropdownHover();
			$(this).addClass('hover-initialized');
		});
	};

	// Handle textarea autosize
	var autosize = require('autosize');
	var _handle_textarea_autosize = function () {
		if ( typeof(autosize) == "function" ) {
			autosize(document.querySelector('textarea.autosizeme'));
		}
	};

	// Handles Bootstrap Popovers

	// last popep popover
	var _last_poped_popover;
	var _handle_popovers = function () {
		$('.popovers').popover();

		// close last displayed popover
		$(document).on('click.bs.popover.data-api', function (e) {
			if ( _last_poped_popover ) {
				_last_poped_popover.popover('hide');
			}
		});
	};

	// Handles scrollable contents using jQuery SlimScroll plugin.
	var _handle_scrollers = function () {
		exports.init_slim_scroll('.scroller');
	};

	// Handles Image Preview using jQuery Fancybox plugin
	require('jquery.fancybox');
	var _handle_fancybox = function () {
		if ( !jQuery.fancybox ) {
			return;
		}

		if ( $(".fancybox-button").size() > 0 ) {
			$(".fancybox-button").fancybox({
				groupAttr : 'data-rel',
				prevEffect : 'none',
				nextEffect : 'none',
				closeBtn : true,
				helpers : {
					title : {
						type : 'inside'
					}
				}
			});
		}
	};

	// Handles counterup plugin wrapper
	require('jquery.counter-up');
	var _handle_counterup = function () {
		if ( !$().counterUp ) {
			return;
		}

		$("[data-counter='counterup']").counterUp({
			delay : 10,
			time : 1000
		});
	};

	// Fix input placeholder issue for IE8 and IE9
	var _handle_fix_input_placeholder_for_ie = function () {
		//fix html5 placeholder attribute for ie7 & ie8
		if ( _is_ie8 || _is_ie9 ) { // ie8 & ie9
			// this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
			$('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function () {
				var input = $(this);

				if ( input.val() === '' && input.attr("placeholder") !== '' ) {
					input.addClass("placeholder").val(input.attr('placeholder'));
				}

				input.focus(function () {
					if ( input.val() == input.attr('placeholder') ) {
						input.val('');
					}
				});

				input.blur(function () {
					if ( input.val() === '' || input.val() == input.attr('placeholder') ) {
						input.val(input.attr('placeholder'));
					}
				});
			});
		}
	};

	// Handle Select2 Dropdowns
	var _handle_select2 = function () {
		if ( $().select2 ) {
			$.fn.select2.defaults.set("theme", "bootstrap");
			$('.select2me').select2({
				placeholder : "Select",
				width : 'auto',
				allowClear : true
			});
		}
	};

	// handle group element heights
	var _handle_height = function () {
		$('[data-auto-height]').each(function () {
			var parent = $(this);
			var items = $('[data-height]', parent);
			var height = 0;
			var mode = parent.attr('data-mode');
			var offset = parseInt(parent.attr('data-offset') ? parent.attr('data-offset') : 0);

			items.each(function () {
				if ( $(this).attr('data-height') == "height" ) {
					$(this).css('height', '');
				} else {
					$(this).css('min-height', '');
				}

				var height_ = (mode == 'base-height' ? $(this).outerHeight() : $(this).outerHeight(true));
				if ( height_ > height ) {
					height = height_;
				}
			});

			height = height + offset;

			items.each(function () {
				if ( $(this).attr('data-height') == "height" ) {
					$(this).css('height', height);
				} else {
					$(this).css('min-height', height);
				}
			});

			if ( parent.attr('data-related') ) {
				$(parent.attr('data-related')).css('height', parent.height());
			}
		});
	}

	//* END:CORE HANDLERS *//

	//main function to initiate the theme
	exports.init = function () {
		//IMPORTANT!!!: Do not modify the core handlers call order.

		//Core handlers
		_handle_init(); // initialize core variables
		_handle_on_resize(); // set and handle responsive

		//UI Component handlers
		_handle_material_design(); // handle material design
		_handle_uniform(); // hanfle custom radio & checkboxes
		_handle_iCheck(); // handles custom icheck radio and checkboxes
		_handle_bootstrap_switch(); // handle bootstrap switch plugin
		_handle_scrollers(); // handles slim scrolling contents
		_handle_fancybox(); // handle fancy box
		_handle_select2(); // handle custom Select2 dropdowns
		_handle_portlet_tools(); // handles portlet action bar functionality(refresh, configure, toggle, remove)
		_handle_alerts(); //handle closabled alerts
		_handle_dropdowns(); // handle dropdowns
		_handle_tabs(); // handle tabs
		_handle_tooltips(); // handle bootstrap tooltips
		_handle_popovers(); // handles bootstrap popovers
		_handle_accordions(); //handles accordions
		_handle_modals(); // handle modals
		_handle_bootstrap_confirmation(); // handle bootstrap confirmations
		_handle_textarea_autosize(); // handle autosize textareas
		_handle_counterup(); // handle counterup instances

		//Handle group element heights
		exports.add_resize_handler(_handle_height); // handle auto calculating height on window resize

		// Hacks
		_handle_fix_input_placeholder_for_ie(); //IE8 & IE9 input placeholder issue fix
	};

	//main function to initiate core javascript after ajax complete
	exports.init_ajax = function () {
		_handle_uniform(); // handles custom radio & checkboxes
		_handle_iCheck(); // handles custom icheck radio and checkboxes
		_handle_bootstrap_switch(); // handle bootstrap switch plugin
		_handle_dropdown_hover(); // handles dropdown hover
		_handle_scrollers(); // handles slim scrolling contents
		_handle_select2(); // handle custom Select2 dropdowns
		_handle_fancybox(); // handle fancy box
		_handle_dropdowns(); // handle dropdowns
		_handle_tooltips(); // handle bootstrap tooltips
		_handle_popovers(); // handles bootstrap popovers
		_handle_accordions(); //handles accordions
		_handle_bootstrap_confirmation(); // handle bootstrap confirmations
	};

	//init main components
	exports.init_components = function () {
		this.init_ajax();
	};

	//public function to remember last opened popover that needs to be closed on click
	exports.set_last_poped_popover = function (el) {
		_last_poped_popover = el;
	};

	//public function to add callback a function which will be called on window resize
	exports.add_resize_handler = function (func) {
		_resize_handlers.push(func);
	};

	//public functon to call _runresizeHandlers
	exports.run_resize_handlers = function () {
		_run_resize_handlers();
	};

	// wrApper function to scroll(focus) to an element
	exports.scroll_to = function (el, offeset) {
		var pos = (el && el.size() > 0) ? el.offset().top : 0;

		if ( el ) {
			if ( $('body').hasClass('page-header-fixed') ) {
				pos = pos - $('.page-header').height();
			} else if ( $('body').hasClass('page-header-top-fixed') ) {
				pos = pos - $('.page-header-top').height();
			} else if ( $('body').hasClass('page-header-menu-fixed') ) {
				pos = pos - $('.page-header-menu').height();
			}
			pos = pos + (offeset ? offeset : -1 * el.height());
		}

		$('html,body').animate({
			scrollTop : pos
		}, 'slow');
	};

	exports.init_slim_scroll = function (el) {
		$(el).each(function () {
			if ( $(this).attr("data-initialized") ) {
				return; // exit
			}

			var height;

			if ( $(this).attr("data-height") ) {
				height = $(this).attr("data-height");
			} else {
				height = $(this).css('height');
			}

			$(this).slimScroll({
				allowPageScroll : true, // allow page scroll when the element scroll is ended
				size : '7px',
				color : ($(this).attr("data-handle-color") ? $(this).attr("data-handle-color") : '#bbb'),
				wrapperClass : ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
				railColor : ($(this).attr("data-rail-color") ? $(this).attr("data-rail-color") : '#eaeaea'),
				position : _is_rtl ? 'left' : 'right',
				height : height,
				alwaysVisible : ($(this).attr("data-always-visible") == "1" ? true : false),
				railVisible : ($(this).attr("data-rail-visible") == "1" ? true : false),
				disableFadeOut : true
			});

			$(this).attr("data-initialized", "1");
		});
	};

	exports.destroy_slim_scroll = function (el) {
		$(el).each(function () {
			if ( $(this).attr("data-initialized") === "1" ) { // destroy existing instance before updating the height
				$(this).removeAttr("data-initialized");
				$(this).removeAttr("style");

				var attrList = {};

				// store the custom attribures so later we will reassign.
				if ( $(this).attr("data-handle-color") ) {
					attrList["data-handle-color"] = $(this).attr("data-handle-color");
				}
				if ( $(this).attr("data-wrapper-class") ) {
					attrList["data-wrapper-class"] = $(this).attr("data-wrapper-class");
				}
				if ( $(this).attr("data-rail-color") ) {
					attrList["data-rail-color"] = $(this).attr("data-rail-color");
				}
				if ( $(this).attr("data-always-visible") ) {
					attrList["data-always-visible"] = $(this).attr("data-always-visible");
				}
				if ( $(this).attr("data-rail-visible") ) {
					attrList["data-rail-visible"] = $(this).attr("data-rail-visible");
				}

				$(this).slimScroll({
					wrapperClass : ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
					destroy : true
				});

				var the = $(this);

				// reassign custom attributes
				$.each(attrList, function (key, value) {
					the.attr(key, value);
				});

			}
		});
	};

	// function to scroll to the top
	exports.scroll_top = function () {
		exports.scroll_to();
	};

	// wrApper function to  block element(indicate loading)
	exports.block_ui = function (options) {
		options = $.extend(true, {}, options);
		var html = '';
		if ( options.animate ) {
			html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
		} else if ( options.iconOnly ) {
			html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""></div>';
		} else if ( options.textOnly ) {
			html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
		} else {
			html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
		}

		if ( options.target ) { // element blocking
			var el = $(options.target);
			if ( el.height() <= ($(window).height()) ) {
				options.cenrerY = true;
			}
			el.block({
				message : html,
				baseZ : options.zIndex ? options.zIndex : 1000,
				centerY : options.cenrerY !== undefined ? options.cenrerY : false,
				css : {
					top : '10%',
					border : '0',
					padding : '0',
					backgroundColor : 'none'
				},
				overlayCSS : {
					backgroundColor : options.overlayColor ? options.overlayColor : '#555',
					opacity : options.boxed ? 0.05 : 0.1,
					cursor : 'wait'
				}
			});
		} else { // page blocking
			$.block_ui({
				message : html,
				baseZ : options.zIndex ? options.zIndex : 1000,
				css : {
					border : '0',
					padding : '0',
					backgroundColor : 'none'
				},
				overlayCSS : {
					backgroundColor : options.overlayColor ? options.overlayColor : '#555',
					opacity : options.boxed ? 0.05 : 0.1,
					cursor : 'wait'
				}
			});
		}
	};

	// wrApper function to  un-block element(finish loading)
	exports.unblock_ui = function (target) {
		if ( target ) {
			$(target).unblock({
				onUnblock : function () {
					$(target).css('position', '');
					$(target).css('zoom', '');
				}
			});
		} else {
			$.unblock_ui();
		}
	};

	exports.start_page_loading = function (options) {
		if ( options && options.animate ) {
			$('.page-spinner-bar').remove();
			$('body').append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
		} else {
			$('.page-loading').remove();
			$('body').append('<div class="page-loading"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>' + (options && options.message ? options.message : 'Loading...') + '</span></div>');
		}
	};

	exports.stop_page_loading = function () {
		$('.page-loading, .page-spinner-bar').remove();
	};

	exports.alert = function (options) {

		options = $.extend(true, {
			container : "", // alerts parent container(by default placed after the page breadcrumbs)
			place : "append", // "append" or "prepend" in container
			type : 'success', // alert's type
			message : "", // alert's message
			close : true, // make alert closable
			reset : true, // close all previouse alerts first
			focus : true, // auto scroll to the alert after shown
			closeInSeconds : 0, // auto close after defined seconds
			icon : "" // put icon before the message
		}, options);

		var id = util.get_unique_id("App_alert");

		var html = '<div id="' + id + '" class="custom-alerts alert alert-' + options.type + ' fade in">' + (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : '') + (options.icon !== "" ? '<i class="fa-lg fa fa-' + options.icon + '"></i>  ' : '') + options.message + '</div>';

		if ( options.reset ) {
			$('.custom-alerts').remove();
		}

		if ( !options.container ) {
			if ( $('body').hasClass("page-container-bg-solid") || $('body').hasClass("page-content-white") ) {
				$('.page-title').after(html);
			} else {
				if ( $('.page-bar').size() > 0 ) {
					$('.page-bar').after(html);
				} else {
					$('.page-breadcrumb').after(html);
				}
			}
		} else {
			if ( options.place == "append" ) {
				$(options.container).append(html);
			} else {
				$(options.container).prepend(html);
			}
		}

		if ( options.focus ) {
			exports.scroll_to($('#' + id));
		}

		if ( options.closeInSeconds > 0 ) {
			setTimeout(function () {
				$('#' + id).remove();
			}, options.closeInSeconds * 1000);
		}

		return id;
	};

	// initializes uniform elements
	exports.init_uniform = function (els) {
		if ( els ) {
			$(els).each(function () {
				if ( $(this).parents(".checker").size() === 0 ) {
					$(this).show();
					$(this).uniform();
				}
			});
		} else {
			_handle_uniform();
		}
	};

	//wrApper function to update/sync jquery uniform checkbox & radios
	exports.update_uniform = function (els) {
		$.uniform.update(els); // update the uniform checkbox & radios UI after the actual input control state changed
	};

	//public function to initialize the fancybox plugin
	exports.init_fancybox = function () {
		_handle_fancybox();
	};

	//public helper function to get actual input value(used in IE9 and IE8 due to placeholder attribute not supported)
	exports.get_actual_val = function (el) {
		el = $(el);
		if ( el.val() === el.attr("placeholder") ) {
			return "";
		}
		return el.val();
	};

	// check IE8 mode
	exports.is_ie8 = function () {
		return _is_ie8;
	};

	// check IE9 mode
	exports.is_ie9 = function () {
		return _is_ie9;
	};

	//check RTL mode
	exports.is_rtl = function () {
		return _is_rtl;
	};

	// check IE8 mode
	exports.is_angular_js_app = function () {
		return (typeof angular == 'undefined') ? false : true;
	};

	// get layout color code by color name
	exports.get_brand_color = function (name) {
		if ( brandColors[name] ) {
			return brandColors[name];
		} else {
			return '';
		}
	};

	exports.get_responsive_breakpoint = function (size) {
		// bootstrap responsive breakpoints
		var sizes = {
			'xs' : 480,     // extra small
			'sm' : 768,     // small
			'md' : 992,     // medium
			'lg' : 1200     // large
		};

		return sizes[size] ? sizes[size] : 0;
	};
	exports.getAssetsPath = function () {
		return assetsPath;
	};

	exports.setAssetsPath = function (path) {
		assetsPath = path;
	};

	exports.setGlobalImgPath = function (path) {
		globalImgPath = path;
	};

	exports.getGlobalImgPath = function () {
		return assetsPath + globalImgPath;
	};

	exports.setGlobalPluginsPath = function (path) {
		globalPluginsPath = path;
	};

	exports.getGlobalPluginsPath = function () {
		return assetsPath + globalPluginsPath;
	};

	exports.getGlobalCssPath = function () {
		return assetsPath + globalCssPath;
	};
});