@extends('lemon.template.default')
@section('head-css')
	{!! Html::style('assets/css/lemon/bt3.css') !!}
	{!! Html::style('assets/css/lemon/font-awesome.css') !!}
	{!! Html::style('assets/css/lemon/animate.css') !!}
	{!! Html::style('assets/css/lemon/inspinia.css') !!}
@endsection
@section('body-start')
	<body class="white-bg">@endsection
	@section('body-main')
		@if(isset($input))
		{!!  Session::flashInput($input) !!}
		@endif
		<div id="wrapper">
			<div class="middle-box text-center animated fadeInRightBig">
				<h3 class="font-bold @if (Session::get('end.level') == 'success' ) text-success @endif
				@if (Session::get('end.level') == 'danger' ) text-danger @endif">
					@if (Session::get('end.level') == 'success' )
						<i class="fa fa-check-circle-o"></i>
					@endif
					@if (Session::get('end.level') == 'danger' )
						<i class="fa fa-times-circle-o"></i>
					@endif
					{!! Session::get('end.message') !!}</h3>
				@if (isset($location))
				@if ($location == 'back' || $time == 0)
					<p><a href="javascript:window.history.go(-1);">返回上级</a></p>
				@else
					<p>
						您将在 <span id="clock">0</span>秒内跳转至目标页面, 如果不想等待, <a href="{!! $location !!}">点此立即跳转</a>!
					</p>
					<script>
					requirejs(['jquery'], function ($) {
						$(function () {
							var t = {!! $time !!};//设定跳转的时间
							setInterval(refer(), 1000); //启动1秒定时
							function refer() {
								if ( t == 0 ) {
									window.location.href = "{!! $location !!}"; //设定跳转的链接地址
								}
								$('#clock').text(Math.ceil(t / 1000)); // 显示倒计时
								t -= 1000;
							}
						})
					})
					</script>
				@endif
				@endif
			</div>
		</div>
@endsection