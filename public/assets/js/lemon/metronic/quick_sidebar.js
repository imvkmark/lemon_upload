/**
 Core script to handle the entire theme and core functions
 **/
define(function (require, exports) {
	var app = require('lemon/metronic/app');
	var layout = require('lemon/metronic/layout');

	// Handles quick sidebar toggler
	var _handle_quick_sidebar_toggler = function () {
		// quick sidebar toggler
		$('.dropdown-quick-sidebar-toggler a, .page-quick-sidebar-toggler, .quick-sidebar-toggler').click(function (e) {
			$('body').toggleClass('page-quick-sidebar-open');
		});
	};

	// Handles quick sidebar chats
	var _handle_quick_sidebar_chat = function () {
		var wrapper = $('.page-quick-sidebar-wrapper');
		var wrapperChat = wrapper.find('.page-quick-sidebar-chat');

		var initChatSlimScroll = function () {
			var chatUsers = wrapper.find('.page-quick-sidebar-chat-users');
			var chatUsersHeight;

			chatUsersHeight = wrapper.height() - wrapper.find('.nav-tabs').outerHeight(true);

			// chat user list
			app.destroy_slim_scroll(chatUsers);
			chatUsers.attr("data-height", chatUsersHeight);
			app.init_slim_scroll(chatUsers);

			var chatMessages = wrapperChat.find('.page-quick-sidebar-chat-user-messages');
			var chatMessagesHeight = chatUsersHeight - wrapperChat.find('.page-quick-sidebar-chat-user-form').outerHeight(true);
			chatMessagesHeight = chatMessagesHeight - wrapperChat.find('.page-quick-sidebar-nav').outerHeight(true);

			// user chat messages
			app.destroy_slim_scroll(chatMessages);
			chatMessages.attr("data-height", chatMessagesHeight);
			app.init_slim_scroll(chatMessages);
		};

		initChatSlimScroll();
		app.add_resize_handler(initChatSlimScroll); // reinitialize on window resize

		wrapper.find('.page-quick-sidebar-chat-users .media-list > .media').click(function () {
			wrapperChat.addClass("page-quick-sidebar-content-item-shown");
		});

		wrapper.find('.page-quick-sidebar-chat-user .page-quick-sidebar-back-to-list').click(function () {
			wrapperChat.removeClass("page-quick-sidebar-content-item-shown");
		});

		var handleChatMessagePost = function (e) {
			e.preventDefault();

			var chatContainer = wrapperChat.find(".page-quick-sidebar-chat-user-messages");
			var input = wrapperChat.find('.page-quick-sidebar-chat-user-form .form-control');

			var text = input.val();
			if ( text.length === 0 ) {
				return;
			}

			var preparePost = function (dir, time, name, avatar, message) {
				var tpl = '';
				tpl += '<div class="post ' + dir + '">';
				tpl += '<img class="avatar" alt="" src="' + layout.get_layout_img_path() + avatar + '.jpg"/>';
				tpl += '<div class="message">';
				tpl += '<span class="arrow"></span>';
				tpl += '<a href="#" class="name">Bob Nilson</a>&nbsp;';
				tpl += '<span class="datetime">' + time + '</span>';
				tpl += '<span class="body">';
				tpl += message;
				tpl += '</span>';
				tpl += '</div>';
				tpl += '</div>';

				return tpl;
			};

			// handle post
			var time = new Date();
			var message = preparePost('out', (time.getHours() + ':' + time.getMinutes()), "Bob Nilson", 'avatar3', text);
			message = $(message);
			chatContainer.append(message);

			chatContainer.slimScroll({
				scrollTo : '1000000px'
			});

			input.val("");

			// simulate reply
			setTimeout(function () {
				var time = new Date();
				var message = preparePost('in', (time.getHours() + ':' + time.getMinutes()), "Ella Wong", 'avatar2', 'Lorem ipsum doloriam nibh...');
				message = $(message);
				chatContainer.append(message);

				chatContainer.slimScroll({
					scrollTo : '1000000px'
				});
			}, 3000);
		};

		wrapperChat.find('.page-quick-sidebar-chat-user-form .btn').click(handleChatMessagePost);
		wrapperChat.find('.page-quick-sidebar-chat-user-form .form-control').keypress(function (e) {
			if ( e.which == 13 ) {
				handleChatMessagePost(e);
				return false;
			}
		});
	};

	// Handles quick sidebar tasks
	var _handle_quick_sidebar_alerts = function () {
		var wrapper = $('.page-quick-sidebar-wrapper');
		var wrapperAlerts = wrapper.find('.page-quick-sidebar-alerts');

		var initAlertsSlimScroll = function () {
			var alertList = wrapper.find('.page-quick-sidebar-alerts-list');
			var alertListHeight;

			alertListHeight = wrapper.height() - wrapper.find('.nav-justified > .nav-tabs').outerHeight();

			// alerts list
			app.destroy_slim_scroll(alertList);
			alertList.attr("data-height", alertListHeight);
			app.init_slim_scroll(alertList);
		};

		initAlertsSlimScroll();
		app.add_resize_handler(initAlertsSlimScroll); // reinitialize on window resize
	};

	// Handles quick sidebar settings
	var _handle_quick_sidebar_settings = function () {
		var wrapper = $('.page-quick-sidebar-wrapper');
		var wrapperAlerts = wrapper.find('.page-quick-sidebar-settings');

		var initSettingsSlimScroll = function () {
			var settingsList = wrapper.find('.page-quick-sidebar-settings-list');
			var settingsListHeight;

			settingsListHeight = wrapper.height() - wrapper.find('.nav-justified > .nav-tabs').outerHeight();

			// alerts list
			app.destroy_slim_scroll(settingsList);
			settingsList.attr("data-height", settingsListHeight);
			app.init_slim_scroll(settingsList);
		};

		initSettingsSlimScroll();
		app.add_resize_handler(initSettingsSlimScroll); // reinitialize on window resize
	};

	exports.init = function () {
		//layout handlers
		_handle_quick_sidebar_toggler(); // handles quick sidebar's toggler
		_handle_quick_sidebar_chat(); // handles quick sidebar's chats
		_handle_quick_sidebar_alerts(); // handles quick sidebar's alerts
		_handle_quick_sidebar_settings(); // handles quick sidebar's setting
	};

});