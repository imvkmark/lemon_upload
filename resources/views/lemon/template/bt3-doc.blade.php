@extends('lemon.template.default')
@section('head-css')
	{!! Html::style('assets/css/lemon/bt3.css') !!}
	{!! Html::style('assets/css/lemon/plugin.css') !!}
	{!! Html::style('assets/css/lemon/font-awesome.css') !!}
	{!! Html::style('assets/css/lemon/bt3-doc.css') !!}
@endsection
@section('body-main')
	@yield('bt3-doc-main')
@endsection
@section('script-cp')
	<script>require(['jquery', 'jquery.bt3'])</script>
@endsection