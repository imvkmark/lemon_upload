/**
 Demo script to handle the theme demo
 **/
define(function (require, exports) {

	var util = require('lemon/util');
	var app = require('lemon/metronic/app');
	var layout = require('lemon/metronic/layout');

	// Handle Theme Settings
	var _handle_theme = function () {

		var panel = $('.theme-panel');

		if ( $('body').hasClass('page-boxed') === false ) {
			$('.layout-option', panel).val("fluid");
		}

		$('.sidebar-option', panel).val("default");
		$('.page-header-option', panel).val("fixed");
		$('.page-footer-option', panel).val("default");
		if ( $('.sidebar-pos-option').attr("disabled") === false ) {
			$('.sidebar-pos-option', panel).val(app.is_rtl() ? 'right' : 'left');
		}

		//handle theme layout
		var resetLayout = function () {
			$("body").removeClass("page-boxed").removeClass("page-footer-fixed").removeClass("page-sidebar-fixed").removeClass("page-header-fixed").removeClass("page-sidebar-reversed");

			$('.page-header > .page-header-inner').removeClass("container");

			if ( $('.page-container').parent(".container").size() === 1 ) {
				$('.page-container').insertAfter('body > .clearfix');
			}

			if ( $('.page-footer > .container').size() === 1 ) {
				$('.page-footer').html($('.page-footer > .container').html());
			} else if ( $('.page-footer').parent(".container").size() === 1 ) {
				$('.page-footer').insertAfter('.page-container');
				$('.scroll-to-top').insertAfter('.page-footer');
			}

			$(".top-menu > .navbar-nav > li.dropdown").removeClass("dropdown-dark");

			$('body > .container').remove();
		};

		var lastSelectedLayout = '';

		var setLayout = function () {

			var layoutOption = $('.layout-option', panel).val();
			var sidebarOption = $('.sidebar-option', panel).val();
			var headerOption = $('.page-header-option', panel).val();
			var footerOption = $('.page-footer-option', panel).val();
			var sidebarPosOption = $('.sidebar-pos-option', panel).val();
			var sidebarStyleOption = $('.sidebar-style-option', panel).val();
			var sidebarMenuOption = $('.sidebar-menu-option', panel).val();
			var headerTopDropdownStyle = $('.page-header-top-dropdown-style-option', panel).val();

			if ( sidebarOption == "fixed" && headerOption == "default" ) {
				alert('Default Header with Fixed Sidebar option is not supported. Proceed with Fixed Header with Fixed Sidebar.');
				$('.page-header-option', panel).val("fixed");
				$('.sidebar-option', panel).val("fixed");
				sidebarOption = 'fixed';
				headerOption = 'fixed';
			}

			resetLayout(); // reset layout to default state

			if ( layoutOption === "boxed" ) {
				$("body").addClass("page-boxed");

				// set header
				$('.page-header > .page-header-inner').addClass("container");
				var cont = $('body > .clearfix').after('<div class="container"></div>');

				// set content
				$('.page-container').appendTo('body > .container');

				// set footer
				if ( footerOption === 'fixed' ) {
					$('.page-footer').html('<div class="container">' + $('.page-footer').html() + '</div>');
				} else {
					$('.page-footer').appendTo('body > .container');
				}
			}

			if ( lastSelectedLayout != layoutOption ) {
				//layout changed, run responsive handler:
				app.run_resize_handlers();
			}
			lastSelectedLayout = layoutOption;

			//header
			if ( headerOption === 'fixed' ) {
				$("body").addClass("page-header-fixed");
				$(".page-header").removeClass("navbar-static-top").addClass("navbar-fixed-top");
			} else {
				$("body").removeClass("page-header-fixed");
				$(".page-header").removeClass("navbar-fixed-top").addClass("navbar-static-top");
			}

			//sidebar
			if ( $('body').hasClass('page-full-width') === false ) {
				if ( sidebarOption === 'fixed' ) {
					$("body").addClass("page-sidebar-fixed");
					$("page-sidebar-menu").addClass("page-sidebar-menu-fixed");
					$("page-sidebar-menu").removeClass("page-sidebar-menu-default");
					layout.init_fixed_sidebar_hover_effect();
				} else {
					$("body").removeClass("page-sidebar-fixed");
					$("page-sidebar-menu").addClass("page-sidebar-menu-default");
					$("page-sidebar-menu").removeClass("page-sidebar-menu-fixed");
					$('.page-sidebar-menu').unbind('mouseenter').unbind('mouseleave');
				}
			}

			// top dropdown style
			if ( headerTopDropdownStyle === 'dark' ) {
				$(".top-menu > .navbar-nav > li.dropdown").addClass("dropdown-dark");
			} else {
				$(".top-menu > .navbar-nav > li.dropdown").removeClass("dropdown-dark");
			}

			//footer
			if ( footerOption === 'fixed' ) {
				$("body").addClass("page-footer-fixed");
			} else {
				$("body").removeClass("page-footer-fixed");
			}

			//sidebar style
			if ( sidebarStyleOption === 'compact' ) {
				$(".page-sidebar-menu").addClass("page-sidebar-menu-compact");
			} else {
				$(".page-sidebar-menu").removeClass("page-sidebar-menu-compact");
			}

			//sidebar menu
			if ( sidebarMenuOption === 'hover' ) {
				if ( sidebarOption == 'fixed' ) {
					$('.sidebar-menu-option', panel).val("accordion");
					alert("Hover Sidebar Menu is not compatible with Fixed Sidebar Mode. Select Default Sidebar Mode Instead.");
				} else {
					$(".page-sidebar-menu").addClass("page-sidebar-menu-hover-submenu");
				}
			} else {
				$(".page-sidebar-menu").removeClass("page-sidebar-menu-hover-submenu");
			}

			//sidebar position
			if ( app.is_rtl() ) {
				if ( sidebarPosOption === 'left' ) {
					$("body").addClass("page-sidebar-reversed");
					$('#frontend-link').tooltip('destroy').tooltip({
						placement : 'right'
					});
				} else {
					$("body").removeClass("page-sidebar-reversed");
					$('#frontend-link').tooltip('destroy').tooltip({
						placement : 'left'
					});
				}
			} else {
				if ( sidebarPosOption === 'right' ) {
					$("body").addClass("page-sidebar-reversed");
					$('#frontend-link').tooltip('destroy').tooltip({
						placement : 'left'
					});
				} else {
					$("body").removeClass("page-sidebar-reversed");
					$('#frontend-link').tooltip('destroy').tooltip({
						placement : 'right'
					});
				}
			}

			layout.fix_content_height(); // fix content height
			layout.init_fixed_sidebar(); // reinitialize fixed sidebar
		};

		// handle theme colors
		var setColor = function (color) {
			var color_ = (app.is_rtl() ? color + '-rtl' : color);
			$('#style_color').attr("href", layout.get_layout_css_path() + 'themes/' + color_ + ".min.css");
		};

		$('.theme-colors > li', panel).click(function () {
			var color = $(this).attr("data-theme");
			setColor(color);
			$('ul > li', panel).removeClass("active");
			$(this).addClass("active");

			if ( color === 'dark' ) {
				$('.page-actions .btn').removeClass('red-haze').addClass('btn-default btn-transparent');
			} else {
				$('.page-actions .btn').removeClass('btn-default btn-transparent').addClass('red-haze');
			}
		});

		// set default theme options:

		if ( $("body").hasClass("page-boxed") ) {
			$('.layout-option', panel).val("boxed");
		}

		if ( $("body").hasClass("page-sidebar-fixed") ) {
			$('.sidebar-option', panel).val("fixed");
		}

		if ( $("body").hasClass("page-header-fixed") ) {
			$('.page-header-option', panel).val("fixed");
		}

		if ( $("body").hasClass("page-footer-fixed") ) {
			$('.page-footer-option', panel).val("fixed");
		}

		if ( $("body").hasClass("page-sidebar-reversed") ) {
			$('.sidebar-pos-option', panel).val("right");
		}

		if ( $(".page-sidebar-menu").hasClass("page-sidebar-menu-light") ) {
			$('.sidebar-style-option', panel).val("light");
		}

		if ( $(".page-sidebar-menu").hasClass("page-sidebar-menu-hover-submenu") ) {
			$('.sidebar-menu-option', panel).val("hover");
		}

		var sidebarOption = $('.sidebar-option', panel).val();
		var headerOption = $('.page-header-option', panel).val();
		var footerOption = $('.page-footer-option', panel).val();
		var sidebarPosOption = $('.sidebar-pos-option', panel).val();
		var sidebarStyleOption = $('.sidebar-style-option', panel).val();
		var sidebarMenuOption = $('.sidebar-menu-option', panel).val();

		$('.layout-option, .page-header-top-dropdown-style-option, .page-header-option, .sidebar-option, .page-footer-option, .sidebar-pos-option, .sidebar-style-option, .sidebar-menu-option', panel).change(setLayout);
	};

	// handle theme style
	var setThemeStyle = function (style) {
		var file = (style === 'rounded' ? 'components-rounded' : 'components');
		file = (app.is_rtl() ? file + '-rtl' : file);

		$('#style_components').attr("href", app.getGlobalCssPath() + file + ".min.css");

		util.cookie_raw('layout-style-option', style);

	};

	//main function to initiate the theme
	exports.init = function () {
		// handles style customer tool
		_handle_theme();

		// handle layout style change
		$('.theme-panel .layout-style-option').change(function () {
			setThemeStyle($(this).val());
		});

		// set layout style from cookie
		if ( util.cookie_raw('layout-style-option') === 'rounded' ) {
			setThemeStyle(util.cookie_raw('layout-style-option'));
			$('.theme-panel .layout-style-option').val(util.cookie_raw('layout-style-option'));
		}
	};

});