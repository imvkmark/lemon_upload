@extends('lemon.template.desktop')
@section('head-css')
	{!! Html::style('assets/css/desktop/login.css') !!}
	{!! Html::style('assets/css/lemon/plugin.css') !!}
@endsection
@section('desktop-main')
	<div class="loginBox">
		{!! Form::open(['route' => 'dsk_lemon_home.login','id' => 'form_login']) !!}
		<div class="username">
			<h5>{!! Form::label('adm_name', trans('desktop.login.username')) !!}</h5>
			{!! Form::text('adm_name', null, ['class'=>'text span-7']) !!}
		</div>
		<div class="password">
			<h5>{!! Form::label('adm_pwd', trans('desktop.login.password')) !!}:</h5>
			{!! Form::password('adm_pwd', ['class'=>'text span-7']) !!}
		</div>
		<div class="button">
			{!! Form::button('<span>&nbsp;</span>', ['class'=>'btnEnter', 'type'=>'submit']) !!}
		</div>
		<div class="back"><a href="{{ url('/') }}" target="_blank">{!!trans('site.homepage')!!}</a></div>
		{!! Form::close() !!}
	</div>
	<script type="text/javascript">
	require(['jquery', 'lemon/util', 'jquery.validate', 'jquery.form'], function ($, util) {
		$(function () {
			// location
			if ( top.location != this.location ) {
				top.location.href = this.location.href;
			}

			// validate
			var conf = util.validate_conf({
				rules : {
					adm_name : {
						required : true
					},
					adm_pwd : {
						required : true
					}
				},
				messages : {
					adm_name : {
						required : '请输入用户名'
					},
					adm_pwd : {
						required : '请输入密码'
					}
				}
			}, 'bt3_inline');
			$("#form_login").validate(conf);
		})
	});
	</script>
@endsection
@section('cp')
@overwrite