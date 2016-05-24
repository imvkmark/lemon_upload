@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.image_key.header')
		@if (isset($item))
			{!! Form::model($item,['route' => ['dsk_image_key.edit', $item->id], 'id' => 'form_image_key']) !!}
		@else
			{!! Form::open(['route' => 'dsk_image_key.create','id' => 'form_image_key']) !!}
		@endif
		<table class="table">
			<tr>
				<td class="w108">{!! Form::label('account_id', '开发者ID', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::select('account_id', $develops) !!}</td>
			</tr>
			<tr>
				<td class="w108">{!! Form::label('key_type', '密钥类型', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::select('key_type', \App\Models\PluginImageKey::typeLinear(), null, ['placeholder'=> '请选择密钥类型']) !!}</td>
			</tr>
			<tr>
				<td class="w108">{!! Form::label('key_public', '用户号', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::text('key_public', $key_public, ['readonly'=> 'readonly', 'disabled' => 'disabled']) !!}</td>
			</tr>
			<tr>
				<td class="w108">{!! Form::label('key_secret', '用户密钥', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::text('key_secret', null, ['placeholder' => '用户密钥']) !!}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{!! Form::button('<span>'.(isset($item) ? '编辑' : '添加').'</span>', ['class'=>'btn-small', 'type'=> 'submit']) !!}  </td>
			</tr>
		</table>
		{!! Form::close() !!}
		<script>
		requirejs(['jquery', 'lemon/util', 'jquery.validate', 'jquery.form'], function ($, util) {
			var conf = util.validate_conf({
				rules : {
					'image_key_secret' : {
						required : true
					},
					'account_id' : {
						required : true
					},
					'image_key_type' : {
						required : true
					}
				}
			}, 'jquery.form');
			$('#form_image_key').validate(conf);
		});
		</script>
	</div>
@endsection