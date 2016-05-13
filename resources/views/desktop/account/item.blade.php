@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.account.header')
		@if (isset($item))
			{!! Form::model($item,['route' => ['dsk_account.edit', $item['account_id']], 'id' => 'form_item']) !!}
		@else
			{!! Form::open(['route' => 'dsk_account.create','id' => 'form_item', 'method' => 'post']) !!}
		@endif
		{!!Form::hidden('account_type', $account_type)!!}
		<table class="table">
			<tr>
				<td class="w120">{!! Form::label('account_name', trans('desktop.account.account_name'), ['class' => 'strong validation']) !!}</td>
				<?php
				$options = [];
				if (isset($item)) {
					$options['readonly'] = 'readonly';
					$options['disabled'] = 'disabled';
				}
				?>
				<td>{!! Form::text('account_name', null, $options) !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('password', '密码', ['class' => 'strong '.(!isset($item) ? 'validation' : '')]) !!}</td>
				<td>{!! Form::password('password', null) !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('password_confirmation', '重复密码', ['class' => 'strong '.(!isset($item) ? 'validation' : '')]) !!}</td>
				<td>{!! Form::password('password_confirmation', null) !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('role_id', '用户角色', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::select('role_id', $roles,  null) !!}</td>
			</tr>
			@if ($account_type == \App\Models\PamAccount::ACCOUNT_TYPE_DESKTOP)
				<tr>
					<td>{!! Form::label('desktop[qq]', 'QQ', ['class' => 'strong']) !!}</td>
					<td>{!! Form::text('desktop[qq]') !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('desktop[mobile]', '联系方式', ['class' => 'strong']) !!}</td>
					<td>{!! Form::text('desktop[mobile]') !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('desktop[realname]', '真实姓名', ['class' => 'strong']) !!}</td>
					<td>{!! Form::text('desktop[realname]') !!}</td>
				</tr>
			@endif
			@if ($account_type == \App\Models\PamAccount::ACCOUNT_TYPE_FRONT)
				<tr>
					<td>{!! Form::label('payword', '支付密码', ['class' => 'strong '.(!isset($item) ? 'validation' : '')]) !!}</td>
					<td>{!! Form::password('front[payword]', ['id' => 'payword']) !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('payword_confirmation', '重复支付密码', ['class' => 'strong '.(!isset($item) ? 'validation' : '')]) !!}</td>
					<td>{!! Form::password('front[payword_confirmation]', null) !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('front[qq]', 'QQ', ['class' => 'strong']) !!}</td>
					<td>{!! Form::text('front[qq]') !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('front[mobile]', '联系方式', ['class' => 'strong']) !!}</td>
					<td>{!! Form::text('front[mobile]') !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('front[address]', '地址', ['class' => 'strong']) !!}</td>
					<td>{!! Form::text('front[address]') !!}</td>
				</tr>
			@endif
			@if ($account_type == \App\Models\PamAccount::ACCOUNT_TYPE_DEVELOP)
				<tr>
					<td>{!! Form::label('develop[nickname]', '虚拟名称', ['class' => 'strong place']) !!}</td>
					<td>{!! Form::text('develop[nickname]') !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('develop[truename]', '真实姓名', ['class' => 'strong place']) !!}</td>
					<td>{!! Form::text('develop[truename]') !!}</td>
				</tr>
				<tr>
					<td>{!! Form::label('develop[email]', '邮箱地址', ['class' => 'strong validation']) !!}</td>
					<td>{!! Form::text('develop[email]') !!}</td>
				</tr>
			@endif
			<tr>
				<td>&nbsp;</td>
				<td>{!! Form::button('<span>'.(isset($item) ? '编辑' : '添加').'</span>', ['class'=>'btn-small', 'type'=> 'submit']) !!}</td>
			</tr>
		</table>
		{!! Form::close() !!}
		<script>
		require(['jquery', 'lemon/util', 'jquery.validate', 'jquery.form'], function ($, util) {
			var conf = util.validate_conf({
				rules : {
					@if (!isset($item))
					account_name : {
						required : true,
						remote : "{{route('support_validate.account_name_available')}}"
					},
					@endif
					password : {
						@if (!isset($item)) required : true @endif
					},
					password_confirmation : {
						@if (!isset($item)) required : true, @endif
						equalTo : '#password'
					}
					@if ($account_type == \App\Models\PamAccount::ACCOUNT_TYPE_DESKTOP)
					,
					'desktop[qq]' : {qq : true},
					'desktop[mobile]' : {mobile : true}
					@endif
					@if ($account_type == \App\Models\PamAccount::ACCOUNT_TYPE_FRONT)
					,
					'front[qq]' : {qq : true},
					'front[mobile]' : {mobile : true},
					'front[payword]' : {},
					'front[payword_confirmation]' : {equalTo : '#payword'}
					@endif
					@if ($account_type == \App\Models\PamAccount::ACCOUNT_TYPE_DEVELOP)
					,
					'develop[email]' : {
						email : true,
						required : true
					}
					@endif
				},
				messages : {
					account_name : {
						required : '此项必填',
						remote : "账户名重复, 请重新填写"
					}
				}
			}, 'form');
			$('#form_item').validate(conf);
		});
		</script>
	</div>
@endsection