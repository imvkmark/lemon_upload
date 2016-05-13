@extends('lemon.template.default')
@section('head-css')
	{!! Html::style('assets/css/lemon/bt3.css') !!}
	{!! Html::style('assets/css/lemon/font-awesome.css') !!}
	{!! Html::style('assets/css/lemon/plugin.css') !!}
@endsection
@section('body-main')
	<div class="container">
		@yield('bootstrap-main')
	</div>
@endsection
@section('script-cp')
	<script>
	require(['lemon/doc', 'jquery.bt3'], function (doc) {
		doc.fill_and_highlight('J_scriptSource', 'J_script', 'script');
		doc.trim_content('J_html');
	})
	</script>
@endsection