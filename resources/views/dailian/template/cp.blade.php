@extends('lemon.template.default')
@section('head-css')
	{!! Html::style('assets/css/lemon/bt3.css') !!}
	{!! Html::style('assets/css/lemon/font-awesome.css') !!}
	{!! Html::style('assets/css/lemon/plugin.css') !!}
	{!! Html::style('assets/css/lemon/animate.css') !!}
	{!! Html::style('assets/css/lemon/inspinia.css') !!}
@endsection
@section('body-main')
	@include('lemon.inc.toastr')
	<div id="wrapper">
		@include('front.inc.nav')
		<div id="page-wrapper" class="gray-bg">
			@include('front.inc.topNav')
			<div class="wrapper wrapper-content animated fadeInRight">
				@yield('dailian_cp-main')
			</div>
			@include('front.inc.footer')
		</div>
		{{--@include('site.inc.sidebar')--}}
	</div>
@endsection
@section('script-cp')
	<script>requirejs(['1dailian/front_cp']);</script>
@endsection