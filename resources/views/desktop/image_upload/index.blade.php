@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.image_upload.header')
		<div>
		@if ($items->total())
			<!-- 数据表格 -->
				<table class="table">
					<tr class="thead-space">
						<th class="w72">开发者账户ID</th>
						<th>类型</th>
						<th>Key</th>
						<th>密钥</th>
						<th class="w108">操作</th>
					</tr>
					@foreach($items as $item)
						<tr>
							<td>{{$item->account_id}}</td>
							<td>{!! Form::showThumb($item->upload_path)  !!}</td>
							<td>{{$item->image_width}}</td>
							<td>{{$item->image_height}}</td>
							<td>{{ \App\Lemon\Repositories\Sour\LmUtil::formatBytes($item->upload_filesize, 2)}}</td>
							<td>
								<a class="fa fa-remove fa-lg red J_request" data-confirm="您是否要删除 {{$item->id }} 图片 ?" href="{{route('dsk_image_upload.destroy', [$item->id])}}"></a>
							</td>
						</tr>
					@endforeach
				</table>
				<!-- 分页 -->
				@if ($items->hasPages())
					<div class="mt10">{!!$items->render()!!}</div>
				@endif
			@else
				@include('desktop.inc.empty')
			@endif
		</div>
	</div>
@endsection