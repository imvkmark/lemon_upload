@extends('_layout.default')
@section('head-css')
	{!! Html::style('css/lemon/bt3.css') !!}
	{!! Html::style('css/lemon/seajs.css') !!}
	{!! Html::style('css/lemon/font-awesome.css') !!}
	{!! Html::style('css/lemon/bt3-doc.css') !!}
@endsection
@section('body-main')
	@yield('bt3-doc-main')
@endsection
@section('script-cp')
<script>require(['jquery', 'jquery.bt3'])</script>
@endsection