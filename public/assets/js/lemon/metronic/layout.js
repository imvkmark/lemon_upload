/**
 Core script to handle the entire theme and core functions
 **/
define(function (require, exports) {
	var util = require('lemon/util');
	var app = require('lemon/metronic/app');
	var $ = require('jquery');

	var layoutImgPath = 'layouts/layout4/img/';

	var layoutCssPath = 'layouts/layout4/css/';

	var resBreakpointMd = app.get_responsive_breakpoint('md');

	//* BEGIN:CORE HANDLERS *//
	// this function handles responsive layout on screen size resize or mobile device rotate.

	// Set proper height for sidebar and content. The content and sidebar height must be synced always.
	var _handle_sidebar_and_content_height = function () {
		var content = $('.page-content');
		var sidebar = $('.page-sidebar');
		var body = $('body');
		var height;

		if ( body.hasClass("page-footer-fixed") === true && body.hasClass("page-sidebar-fixed") === false ) {
			var available_height = util.get_viewport().height - $('.page-footer').outerHeight(true) - $('.page-header').outerHeight(true);
			if ( content.height() < available_height ) {
				content.attr('style', 'min-height:' + available_height + 'px');
			}
		} else {
			if ( body.hasClass('page-sidebar-fixed') ) {
				height = _calculateFixedSidebarViewportHeight() - 10;
				if ( body.hasClass('page-footer-fixed') === false ) {
					height = height - $('.page-footer').outerHeight(true);
				}
			} else {
				var headerHeight = $('.page-header').outerHeight(true);
				var footerHeight = $('.page-footer').outerHeight(true);

				if ( util.get_viewport().width < resBreakpointMd ) {
					height = util.get_viewport().height - headerHeight - footerHeight;
				} else {
					height = sidebar.height() - 10;
				}

				if ( (height + headerHeight + footerHeight) <= util.get_viewport().height ) {
					height = util.get_viewport().height - headerHeight - footerHeight - 45;
				}
			}
			content.attr('style', 'min-height:' + height + 'px');
		}
	};

	// Handle sidebar menu links
	var _handle_sidebar_menu_active_link = function (mode, el) {
		var url = location.hash.toLowerCase();

		var menu = $('.page-sidebar-menu');

		if ( mode === 'click' || mode === 'set' ) {
			el = $(el);
		} else if ( mode === 'match' ) {
			menu.find("li > a").each(function () {
				var path = $(this).attr("href").toLowerCase();
				// url match condition
				if ( path.length > 1 && url.substr(1, path.length - 1) == path.substr(1) ) {
					el = $(this);
					return;
				}
			});
		}

		if ( !el || el.size() == 0 ) {
			return;
		}

		if ( el.attr('href').toLowerCase() === 'javascript:;' || el.attr('href').toLowerCase() === '#' ) {
			return;
		}

		var slideSpeed = parseInt(menu.data("slide-speed"));
		var keepExpand = menu.data("keep-expanded");

		// disable active states
		menu.find('li.active').removeClass('active');
		menu.find('li > a > .selected').remove();

		if ( menu.hasClass('page-sidebar-menu-hover-submenu') === false ) {
			menu.find('li.open').each(function () {
				if ( $(this).children('.sub-menu').size() === 0 ) {
					$(this).removeClass('open');
					$(this).find('> a > .arrow.open').removeClass('open');
				}
			});
		} else {
			menu.find('li.open').removeClass('open');
		}

		el.parents('li').each(function () {
			$(this).addClass('active');
			$(this).find('> a > span.arrow').addClass('open');

			if ( $(this).parent('ul.page-sidebar-menu').size() === 1 ) {
				$(this).find('> a').append('<span class="selected"></span>');
			}

			if ( $(this).children('ul.sub-menu').size() === 1 ) {
				$(this).addClass('open');
			}
		});

		if ( mode === 'click' ) {
			if ( util.get_viewport().width < resBreakpointMd && $('.page-sidebar').hasClass("in") ) { // close the menu on mobile view while laoding a page
				$('.page-header .responsive-toggler').click();
			}
		}
	};

	// Handle sidebar menu
	var _handle_sidebar_menu = function () {
		$('.page-sidebar').on('click', 'li > a', function (e) {

			if ( util.get_viewport().width >= resBreakpointMd && $(this).parents('.page-sidebar-menu-hover-submenu').size() === 1 ) {
				// exit of hover sidebar menu
				return;
			}

			if ( $(this).next().hasClass('sub-menu') === false ) {
				if ( util.get_viewport().width < resBreakpointMd && $('.page-sidebar').hasClass("in") ) {
					// close the menu on mobile view while laoding a page
					$('.page-header .responsive-toggler').click();
				}
				return;
			}

			if ( $(this).next().hasClass('sub-menu always-open') ) {
				return;
			}

			var parent = $(this).parent().parent();
			var the = $(this);
			var menu = $('.page-sidebar-menu');
			var sub = $(this).next();

			var autoScroll = menu.data("auto-scroll");
			var slideSpeed = parseInt(menu.data("slide-speed"));
			var keepExpand = menu.data("keep-expanded");

			if ( keepExpand !== true ) {
				parent.children('li.open').children('a').children('.arrow').removeClass('open');
				parent.children('li.open').children('.sub-menu:not(.always-open)').slideUp(slideSpeed);
				parent.children('li.open').removeClass('open');
			}

			var slideOffeset = -200;

			if ( sub.is(":visible") ) {
				$('.arrow', $(this)).removeClass("open");
				$(this).parent().removeClass("open");
				sub.slideUp(slideSpeed, function () {
					if ( autoScroll === true && $('body').hasClass('page-sidebar-closed') === false ) {
						if ( $('body').hasClass('page-sidebar-fixed') ) {
							menu.slimScroll({
								'scrollTo' : (the.position()).top
							});
						} else {
							app.scroll_to(the, slideOffeset);
						}
					}
					_handle_sidebar_and_content_height();
				});
			} else {
				$('.arrow', $(this)).addClass("open");
				$(this).parent().addClass("open");
				sub.slideDown(slideSpeed, function () {
					if ( autoScroll === true && $('body').hasClass('page-sidebar-closed') === false ) {
						if ( $('body').hasClass('page-sidebar-fixed') ) {
							menu.slimScroll({
								'scrollTo' : (the.position()).top
							});
						} else {
							app.scroll_to(the, slideOffeset);
						}
					}
					_handle_sidebar_and_content_height();
				});
			}

			e.preventDefault();
		});

		// handle menu close for angularjs version
		if ( app.is_angular_js_app() ) {
			$(".page-sidebar-menu li > a").on("click", function (e) {
				if ( util.get_viewport().width < resBreakpointMd && $(this).next().hasClass('sub-menu') === false ) {
					$('.page-header .responsive-toggler').click();
				}
			});
		}

		// handle ajax links within sidebar menu
		$('.page-sidebar').on('click', ' li > a.ajaxify', function (e) {
			e.preventDefault();
			app.scroll_top();

			var url = $(this).attr("href");
			var menuContainer = $('.page-sidebar ul');
			var pageContent = $('.page-content');
			var pageContentBody = $('.page-content .page-content-body');

			menuContainer.children('li.active').removeClass('active');
			menuContainer.children('arrow.open').removeClass('open');

			$(this).parents('li').each(function () {
				$(this).addClass('active');
				$(this).children('a > span.arrow').addClass('open');
			});
			$(this).parents('li').addClass('active');

			if ( util.get_viewport().width < resBreakpointMd && $('.page-sidebar').hasClass("in") ) { // close the menu on mobile view while laoding a page
				$('.page-header .responsive-toggler').click();
			}

			app.start_page_loading();

			var the = $(this);

			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				dataType : "html",
				success : function (res) {

					if ( the.parents('li.open').size() === 0 ) {
						$('.page-sidebar-menu > li.open > a').click();
					}

					app.stop_page_loading();
					pageContentBody.html(res);
					exports.fix_content_height(); // fix content height
					app.init_ajax(); // initialize core stuff
				},
				error : function (xhr, ajaxOptions, thrownError) {
					app.stop_page_loading();
					pageContentBody.html('<h4>Could not load the requested content.</h4>');
				}
			});
		});

		// handle ajax link within main content
		$('.page-content').on('click', '.ajaxify', function (e) {
			e.preventDefault();
			app.scroll_top();

			var url = $(this).attr("href");
			var pageContent = $('.page-content');
			var pageContentBody = $('.page-content .page-content-body');

			app.start_page_loading();

			if ( util.get_viewport().width < resBreakpointMd && $('.page-sidebar').hasClass("in") ) { // close the menu on mobile view while laoding a page
				$('.page-header .responsive-toggler').click();
			}

			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				dataType : "html",
				success : function (res) {
					app.stop_page_loading();
					pageContentBody.html(res);
					exports.fix_content_height(); // fix content height
					app.init_ajax(); // initialize core stuff
				},
				error : function (xhr, ajaxOptions, thrownError) {
					pageContentBody.html('<h4>Could not load the requested content.</h4>');
					app.stop_page_loading();
				}
			});
		});

		// handle scrolling to top on responsive menu toggler click when header is fixed for mobile view
		$(document).on('click', '.page-header-fixed-mobile .responsive-toggler', function () {
			app.scroll_top();
		});
	};

	// Helper function to calculate sidebar height for fixed sidebar layout.
	var _calculateFixedSidebarViewportHeight = function () {
		var sidebarHeight = util.get_viewport().height - $('.page-header').outerHeight(true) - 40;
		if ( $('body').hasClass("page-footer-fixed") ) {
			sidebarHeight = sidebarHeight - $('.page-footer').outerHeight();
		}

		return sidebarHeight;
	};

	// Handles fixed sidebar
	var _handle_fixed_sidebar = function () {
		var menu = $('.page-sidebar-menu');

		app.destroy_slim_scroll(menu);

		if ( $('.page-sidebar-fixed').size() === 0 ) {
			_handle_sidebar_and_content_height();
			return;
		}

		if ( util.get_viewport().width >= resBreakpointMd ) {
			menu.attr("data-height", _calculateFixedSidebarViewportHeight());
			app.init_slim_scroll(menu);
			_handle_sidebar_and_content_height();
		}
	};

	// Handles sidebar toggler to close/hide the sidebar.
	var _handle_fixed_sidebar_hover_effect = function () {
		var body = $('body');
		if ( body.hasClass('page-sidebar-fixed') ) {
			$('.page-sidebar').on('mouseenter', function () {
				if ( body.hasClass('page-sidebar-closed') ) {
					$(this).find('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
				}
			}).on('mouseleave', function () {
				if ( body.hasClass('page-sidebar-closed') ) {
					$(this).find('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
				}
			});
		}
	};

	// Hanles sidebar toggler
	var _handle_sidebar_toggler = function () {
		var body = $('body');
		if ( $.cookie && $.cookie('sidebar_closed') === '1' && util.get_viewport().width >= resBreakpointMd ) {
			$('body').addClass('page-sidebar-closed');
			$('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
		}

		// handle sidebar show/hide
		$('body').on('click', '.sidebar-toggler', function (e) {
			var sidebar = $('.page-sidebar');
			var sidebarMenu = $('.page-sidebar-menu');
			$(".sidebar-search", sidebar).removeClass("open");

			if ( body.hasClass("page-sidebar-closed") ) {
				body.removeClass("page-sidebar-closed");
				sidebarMenu.removeClass("page-sidebar-menu-closed");
				if ( $.cookie ) {
					$.cookie('sidebar_closed', '0');
				}
			} else {
				body.addClass("page-sidebar-closed");
				sidebarMenu.addClass("page-sidebar-menu-closed");
				if ( body.hasClass("page-sidebar-fixed") ) {
					sidebarMenu.trigger("mouseleave");
				}
				if ( $.cookie ) {
					$.cookie('sidebar_closed', '1');
				}
			}

			$(window).trigger('resize');
		});

		_handle_fixed_sidebar_hover_effect();

		// handle the search bar close
		$('.page-sidebar').on('click', '.sidebar-search .remove', function (e) {
			e.preventDefault();
			$('.sidebar-search').removeClass("open");
		});

		// handle the search query submit on enter press
		$('.page-sidebar .sidebar-search').on('keypress', 'input.form-control', function (e) {
			if ( e.which == 13 ) {
				$('.sidebar-search').submit();
				return false; //<---- Add this line
			}
		});

		// handle the search submit(for sidebar search and responsive mode of the header search)
		$('.sidebar-search .submit').on('click', function (e) {
			e.preventDefault();
			if ( $('body').hasClass("page-sidebar-closed") ) {
				if ( $('.sidebar-search').hasClass('open') === false ) {
					if ( $('.page-sidebar-fixed').size() === 1 ) {
						$('.page-sidebar .sidebar-toggler').click(); //trigger sidebar toggle button
					}
					$('.sidebar-search').addClass("open");
				} else {
					$('.sidebar-search').submit();
				}
			} else {
				$('.sidebar-search').submit();
			}
		});

		// handle close on body click
		if ( $('.sidebar-search').size() !== 0 ) {
			$('.sidebar-search .input-group').on('click', function (e) {
				e.stopPropagation();
			});

			$('body').on('click', function () {
				if ( $('.sidebar-search').hasClass('open') ) {
					$('.sidebar-search').removeClass("open");
				}
			});
		}
	};

	// Handles the horizontal menu
	var _handle_header = function () {
		// handle search box expand/collapse
		$('.page-header').on('click', '.search-form', function (e) {
			$(this).addClass("open");
			$(this).find('.form-control').focus();

			/* search 焦点
			$('.page-header .search-form .form-control').on('blur', function (e) {
				$(this).closest('.search-form').removeClass("open");
				$(this).unbind("blur");
			});
			*/
		});

		// handle hor menu search form on enter press
		$('.page-header').on('keypress', '.hor-menu .search-form .form-control', function (e) {
			if ( e.which == 13 ) {
				$(this).closest('.search-form').submit();
				return false;
			}
		});

		// handle header search button click
		$('.page-header').on('mousedown', '.search-form.open .submit', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).closest('.search-form').submit();
		});
	};

	// Handles the go to top button at the footer
	var _handle_go_top = function () {
		var offset = 300;
		var duration = 500;

		if ( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) { // ios supported
			$(window).bind("touchend touchcancel touchleave", function (e) {
				if ( $(this).scrollTop() > offset ) {
					$('.scroll-to-top').fadeIn(duration);
				} else {
					$('.scroll-to-top').fadeOut(duration);
				}
			});
		} else { // general
			$(window).scroll(function () {
				if ( $(this).scrollTop() > offset ) {
					$('.scroll-to-top').fadeIn(duration);
				} else {
					$('.scroll-to-top').fadeOut(duration);
				}
			});
		}

		$('.scroll-to-top').click(function (e) {
			e.preventDefault();
			$('html, body').animate({
				scrollTop : 0
			}, duration);
			return false;
		});
	};

	//* END:CORE HANDLERS *//

	// Main init methods to initialize the layout
	// IMPORTANT!!!: Do not modify the core handlers call order.
	exports.init_header = function () {
		_handle_header(); // handles horizontal menu
	};

	exports.set_sidebar_menu_active_link = function (mode, el) {
		_handle_sidebar_menu_active_link(mode, el);
	};

	exports.init_sidebar = function () {
		//layout handlers
		_handle_fixed_sidebar(); // handles fixed sidebar menu
		_handle_sidebar_menu(); // handles main menu
		_handle_sidebar_toggler(); // handles sidebar hide/show

		if ( app.is_angular_js_app() ) {
			_handle_sidebar_menu_active_link('match'); // init sidebar active links
		}

		app.add_resize_handler(_handle_fixed_sidebar); // reinitialize fixed sidebar on window resize
	};

	exports.init_content = function () {
		return;
	};

	exports.init_footer = function () {
		_handle_go_top(); //handles scroll to top functionality in the footer
	};

	exports.init = function () {
		exports.init_header();
		exports.init_sidebar();
		exports.init_content();
		exports.init_footer();
	};

	//public function to fix the sidebar and content height accordingly
	exports.fix_content_height = function () {
		return;
	};

	exports.init_fixed_sidebar_hover_effect = function () {
		_handle_fixed_sidebar_hover_effect();
	};

	exports.init_fixed_sidebar = function () {
		_handle_fixed_sidebar();
	};

	exports.get_layout_img_path = function () {
		return '';
		// return App.getAssetsPath() + layoutImgPath;
	};

	exports.get_layout_css_path = function () {
		return '';
		// return App.getAssetsPath() + layoutCssPath;
	};

});