@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		<div class="bar-fixed">
			<div class="title-bar">
				<h3>{{trans('desktop.edit_password')}}</h3>
			</div>
		</div>
		<form action="{{route('dsk_home.password')}}" data-rel="account" method="post" id="form_password">
		<table class="table">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="account_id" value="{{$_pam['account_id']}}">
			<tr>
				<td class="w108">
					{!! Form::label('account_name', trans('desktop.account_name'), ['class'=>'strong place']) !!}
				</td>
				<td>{!! Form::text('account_name', $_pam['account_name'], ['disabled', 'readonly']) !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('old_password', '老密码', ['class'=>'strong validation']) !!}</td>
				<td>{!! Form::password('old_password') !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('password', trans('desktop.password'), ['class'=>'strong validation']) !!}</td>
				<td>{!! Form::password('password') !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('password_confirmation', trans('desktop.password_confirmation'), ['class'=>'strong validation']) !!}</td>
				<td>{!! Form::password('password_confirmation') !!}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					{!! Form::button('<span>'.trans('desktop.edit_password').'</span>', ['class'=>'btn-small', 'type'=> 'submit']) !!}
				</td>
			</tr>
		</table>
		</form>
		<script>
		require(['jquery', 'lemon/util', 'jquery.validate', 'jquery.form'], function ($, util) {
			var conf = util.validate_conf({
				rules : {
					'old_password' : {
						required : true
					},
					'password' : {
						required : true
					},
					'password_confirmation' : {
						required : true,
						equalTo : '#password'
					}
				}
			}, 'form');
			$('#form_password').validate(conf);
		});
		</script>
	</div>
@endsection