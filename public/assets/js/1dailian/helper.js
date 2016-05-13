define(function (require) {
	var $ = require('jquery');
	var lemon = require('global');
	return {
		fix_height : function () {
			var heightWithoutNavbar = $("body > #wrapper").height() - 61;
			$(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

			var navbarHeigh = $('nav.navbar-default').height();
			var $page_wrapper = $('#page-wrapper');
			var wrapperHeigh = $page_wrapper.height();

			if ( navbarHeigh > wrapperHeigh ) {
				$page_wrapper.css("min-height", navbarHeigh + "px");
			}

			if ( navbarHeigh < wrapperHeigh ) {
				$page_wrapper.css("min-height", $(window).height() + "px");
			}
		},
		animation_hover : function (element, animation) {
			element = $(element);
			element.hover(
				function () {
					element.addClass('animated ' + animation);
				},
				function () {
					//wait for animation to finish before removing classes
					window.setTimeout(function () {
						element.removeClass('animated ' + animation);
					}, 2000);
				});
		},
		smoothly_menu : function () {
			var $body = $('body');
			if ( !$body.hasClass('mini-navbar') || $body.hasClass('body-small') ) {
				// Hide menu in order to smoothly turn on when maximize menu
				$('#side-menu').hide();
				// For smoothly turn on menu
				setTimeout(
					function () {
						$('#side-menu').fadeIn(500);
					}, 100);
			} else if ( $body.hasClass('fixed-sidebar') ) {
				$('#side-menu').hide();
				setTimeout(
					function () {
						$('#side-menu').fadeIn(500);
					}, 300);
			} else {
				// Remove all inline style from jquery fadeIn function to reset menu state
				$('#side-menu').removeAttr('style');
			}
		},
		/**
		 *
		 * @param game_id
		 * @param server_ctr
		 * @param server_key
		 * @param opts
		 */
		server_html : function (game_id, server_ctr, server_key, opts) {
			$(function () {
				var $game_id = $('#' + game_id);
				$game_id.on('change', function () {
					get_server($(this).val());
				});
				get_server($game_id.val());
			});

			function get_server(game_id) {
				if ( !game_id ) return;
				$.get(lemon.support_url.game_server_html, {game_id : game_id, server_key : server_key, options : opts}, function (data) {
					$('#' + server_ctr).html(data);
				})
			}
		},

		type_html : function (game_id, type_ctr, type_key, opts) {
			$(function () {
				var $game_id = $('#' + game_id);
				$game_id.on('change', function () {
					get_type($(this).val());
				});
				get_type($game_id.val());
			});
			function get_type(game_id) {
				if ( !game_id ) return;
				$.get(lemon.support_url.game_type_html, {game_id : game_id, type_key : type_key, options : opts}, function (data) {
					$('#' + type_ctr).html(data);
				})
			}
		}

	}
});