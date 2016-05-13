@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.feedback.header')
		<div>
			<table width="98%" border="0" cellpadding="5" cellspacing="1" class="table J_hover">
				<tr class="thead thead-space thead-center">
					<th class="w72">ID</th>
					<th>问题标题</th>
					<th>创建时间</th>
					<th>回复</th>
					<th class="w216">管理操作</th>
				</tr>
				@foreach($items as $item)
					<tr>
						<td>{{$item['feedback_id']}}</td>
						<td>{{$item['feedback_title']}}</td>
						<td class='txt-center'>{{$item['created_at']}}</td>
						<td class='txt-center'>
							@if ($item['is_reply'] == 'Y')
								<i class="fa fa-check fa-lg green"></i>
								@else
								<i class="fa fa-fire fa-lg red" ></i>
							@endif
						</td>
						<td class='txt-center'>
							<a class="fa fa-search fa-lg" href="{{route('dsk_feedback.show', ['feedback_id' => $item['feedback_id']])}}"></a>
							<a class="fa fa-remove fa-lg red J_delete" href="{{route('dsk_feedback.destroy', ['feedback_id' => $item['feedback_id']])}}"></a>
						</td>
					</tr>
				@endforeach
			</table>
			<div class="pagination">
				{!!$items->render()!!}
			</div>
		</div>
	</div>
@endsection