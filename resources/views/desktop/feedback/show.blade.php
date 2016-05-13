@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.feedback.header')
		{!! Form::model($item,['route' => ['dsk_feedback.update', $item->feedback_id], 'id' => 'form_feedback', 'method' => 'patch']) !!}
		<table class="table">
			<tr>
				<td class="w72">{!! Form::label('feedback_title', '建议标题', ['class' => 'strong place']) !!}</td>
				<td>{!! Form::text('feedback_title', null, ['class' => 'w240', 'readonly']) !!}</td>
			</tr>
			<tr>
				<td class="w72">{!! Form::label('content', '内容', ['class' => 'strong place']) !!}</td>
				<td>{!! Form::textarea('content', null, ['class' => 'w360', 'readonly']) !!}</td>
			</tr>
			<tr>
				<td class="w72">{!! Form::label('reply_content', '回复内容', ['class' => 'strong validation']) !!}</td>
				<td>{!! Form::textarea('reply_content', null, ['class' => 'w360']) !!}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{!! Form::button('<span>'.(isset($item) ? '回复' : '添加').'</span>', ['class'=>'btn-small', 'type'=> 'submit']) !!}</td>
			</tr>
		</table>
		{!! Form::close() !!}
		<script>
		require(['jquery', 'lemon/util', 'jquery.validate', 'jquery.form'], function ($, util) {
			var conf = util.validate_conf({
				rules : {
					reply_content : {
						required : true
					}
				}
			}, 'form');
			$('#form_feedback').validate(conf);
		});
		</script>
	</div>
@endsection