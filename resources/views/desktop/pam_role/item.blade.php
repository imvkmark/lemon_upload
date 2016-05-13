@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.pam_role.header')
		@if (isset($item))
			{!! Form::model($item,['route' => ['dsk_pam_role.edit', $item->id], 'id' => 'form_role']) !!}
		@else
			{!! Form::open(['route' => 'dsk_pam_role.create','id' => 'form_role']) !!}
		@endif
		<table class="table">
			<tr>
				<td class="w108">{!! Form::label('role_name', '角色标识', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::text('role_name', null) !!}</td>
			</tr>
			<tr>
				<td class="w108">{!! Form::label('role_title', '角色名称', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::text('role_title', null) !!}</td>
			</tr>
			<tr>
				<td>{!! Form::label('account_type', '角色组', ['class' => 'strong validation']) !!}</td>
				<td>
					{!!Form::select('account_type', \App\Models\PamAccount::accountTypeLinear(), !isset($item) ? \Request::input('type') : $item['account_type'])!!}
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{!! Form::button('<span>'.(isset($item) ? '编辑' : '添加').'</span>', ['class'=>'btn-small', 'type'=> 'submit']) !!}</td>
			</tr>
		</table>
		{!! Form::close() !!}
	</div>
@endsection