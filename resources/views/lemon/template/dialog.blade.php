@extends('lemon.template.default')
@section('head-css')
{!! Html::style('assets/css/lemon/bt3.css') !!}
{!! Html::style('assets/css/lemon/font-awesome.css') !!}
{!! Html::style('assets/css/lemon/plugin.css') !!}
{!! Html::style('assets/css/lemon/animate.css') !!}
{!! Html::style('assets/css/lemon/inspinia.css') !!}
@endsection
@section('body-start')<body class="white-bg">@endsection
@section('body-main')
	@include('lemon.inc.toastr')
	<div id="wrapper">
		@yield('lemon_dialog-main')
	</div>
@endsection
@section('script-cp')
  <script>
	requirejs(['jquery', 'lemon/util', '1dailian/front_cp'], function(){
		//这里的时间可以设置短一些，时间越短高度变动时抖动越不明显
//		var interval = window.setTimeout("Lemon._iframe_resize()", 50);
//
//		Lemon._iframe_resize = function () {
//			var current_page_height = window.document.body.scrollHeight;
//			var parent_opener = Util.opener();
//			var current_page = parent_opener.Front.iframe;
//			if (typeof current_page == 'undefined') {
//				window.clearInterval(interval);
//				return false;
//			}
//			current_page.height(current_page_height + 20);
//			current_page.reset();
//		};
	})
	</script>
@endsection
