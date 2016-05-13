@extends('lemon.template.default')
@section('head-css')
	{!! Html::style('assets/css/lemon/font-awesome.css') !!}
	{!! Html::style('assets/css/lemon/plugin.css') !!}
	{!! Html::style('assets/css/desktop/skin.css') !!}
@endsection
@section('body-main')
	@include('lemon.inc.toastr')
	@yield('desktop-main')
@endsection
@section('script-cp')
	<script>require(['lemon/desktop_cp']);</script>
@endsection
