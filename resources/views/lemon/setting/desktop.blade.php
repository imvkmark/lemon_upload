@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('lemon.setting.desktop_header')
		@foreach($groups as $group_key => $group)
			@if (isset($group['_items']))
				{!! Form::open(['url' => $url,'id' => 'form_'.$group_key, 'method' => 'post', 'data-rel'=> $type]) !!}
				<table class="table">
					@foreach($group['_items'] as $item_key => $item)
						<tr>
							<td class="{!! isset($group['first_col_class']) ? $group['first_col_class']: '' !!}">
							{!! $item['_label'] !!}
							{!! $item['_tip'] !!}
							<td>
								{!! $item['_render'] !!}
							</td>
						</tr>
					@endforeach
					<tr>
						<td>&nbsp;</td>
						<td>
							{!! Form::button('<span>提交</span>',['class'=>'btn-small', 'type'=>'submit']) !!}
						</td>
					</tr>
				</table>
				{!!Form::close()!!}
				<script>
				requirejs(['jquery', 'jquery.validate'], function ($) {
					$(function () {
						$('#form_' + '{!! $group_key !!}').validate();
					})
				})
				</script>
			@endif
		@endforeach
	</div>
@endsection