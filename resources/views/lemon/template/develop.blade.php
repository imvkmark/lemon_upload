@extends('lemon.template.default')
@section('head-css')
	{!! Html::style('assets/css/lemon/bt3.css') !!}
	{!! Html::style('assets/css/lemon/font-awesome.css') !!}
	{!! Html::style('assets/css/lemon/plugin.css') !!}
	{!! Html::style('assets/css/develop/develop.css') !!}
@endsection
@section('body-main')
	<div class="container">
		@yield('develop-main')
	</div>
@endsection
@section('script-cp')
<script>requirejs(['jquery', 'jquery.bt3'])</script>
@endsection