@extends('_layout.default')
@section('head-css')
	{!! Html::style('css/lemon/screen.css') !!}
	{!! Html::style('css/lemon/font-awesome.css') !!}
@endsection
@section('body-main')
	<div class="container">
		@yield('screen-main')
	</div>
@endsection