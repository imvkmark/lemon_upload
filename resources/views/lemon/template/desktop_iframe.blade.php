@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-dialog">
		@yield('desktop-iframe-main')
	</div>
	<script>
	//这里的时间可以设置短一些，时间越短高度变动时抖动越不明显
	var interval = window.setInterval("Lemon._dsk_iframe_resize()", 200);

	Lemon._dsk_iframe_resize = function () {
		var current_page_height = window.document.body.scrollHeight;
		var parent_opener = Util.opener();
		var current_page = parent_opener.Desktop.iframe;
		if (typeof current_page == 'undefined') {
			window.clearInterval(interval);
			return false;
		}
		if (current_page_height != current_page.height()) {
			current_page.height(current_page_height);
			current_page.reset();
		}
	};
	</script>
@stop
